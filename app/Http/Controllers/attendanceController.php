<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\attendance;
use App\Models\Branch;
use App\Models\notification;
use App\Models\work_schedule;
use App\Models\work_shift;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class attendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $attendance = attendance::with(['employee.branch', 'work_schedule', 'employee.user']);
        if ($user->role_id == 4) {
            $attendance->whereHas('employee', function ($query) use ($user) {
                $query->where('id', $user->employee_id);
            });
        }
        if ($user->role_id == 3) {
            $attendance->whereHas('employee', function ($query) use ($user) {
                $query->where('branch_id', $user->employee->branch_id);
            });
        }
        if ($request->date) {
            $attendance->where('date', $request->date);
        }
        if ($request->full_name) {
            $attendance->whereHas('employee', function ($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->full_name . '%');
            });
        }
        if ($request->position) {
            $attendance->whereHas('employee', function ($query) use ($request) {
                $query->where('position', 'like', '%' . $request->position . '%');
            });
        }
        if ($request->branch) {
            $attendance->whereHas('employee.branch', function ($query) use ($request) {
                $query->where('id', $request->branch);
            });
        }

        if ($request->ofBranch){
            $attendance->whereHas('employee.user', function ($query) {
                $query->where('role_id', 3);
            });
        }else{
            if ($request->myAttendance){
                $attendance->whereHas('employee', function ($query) use ($user) {
                    $query->where('id', $user->employee_id);
                });
            }else{
                $attendance->whereHas('employee.user', function ($query) {
                    $query->where('role_id', 4);
                });
            }
        }

        $attendance = $attendance->whereNotNull('check_out')->OrderBy('id', 'desc')->paginate(10);
        $work_schedules = work_schedule::with('work_shift')->get();
        $branch = Branch::where('status', 1)->get();
        $employees = Employee::with('user', 'branch')->whereHas('user', function($query){
            $query->where('role_id', 4);
        });
        if ($user->role_id == 3){
            $employees->where('branch_id', $user->employee->branch_id);
        }

        $employees = $employees->get();
        return view('attendance.index', compact('attendance', 'branch', 'employees', 'work_schedules'));
    }
    public function create(Request $request){
        $request->validate([
            'employee_id' => 'required|integer',
            'work_schedule_id' => 'required|integer',
            'date' => 'required|date',
            'check_in' => 'required|date_format:H:i',
            'check_out' => 'required|date_format:H:i',
        ]);

        $checkWorkSchedule = work_schedule::where('employee_id', $request->employee_id)
                                            ->where('id', $request->work_schedule_id)
                                            ->first();

        if(!$checkWorkSchedule){
            return redirect()->back()->with('error', 'Ca làm không hợp lệ với nhân viên bạn chọn');
        }

        $checkIn = new Carbon($request->check_in);
        $checkOut = new Carbon($request->check_out);
        if ($checkOut->lessThan($checkIn)){
            return redirect()->back()->with(['error' => 'Thời gian check-out không thể nhỏ hơn thời gian check-in']);
        }
        $workingHours = $checkIn->diffInHours($checkOut);
        attendance::create([
            'employee_id' => $request->employee_id,
            'work_schedule_id' => $request->work_schedule_id,
            'date' => $request->date,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'working_hours' => $workingHours,
        ]);

        return redirect()->back()->with('success', 'Thêm chấm công thành công');
    }
    public function update(Request $request ,$id){
        $attendance = attendance::findOrFail($id);

        $request->validate([
            'work_schedule_id' => 'required|integer',
            'date' => 'required|date',
            'check_in' => 'required|date_format:H:i:s',
            'check_out' => 'required|date_format:H:i:s',
        ]);
        $checkIn = new Carbon($request->check_in);
        $checkOut = new Carbon($request->check_out);
        $workingHours = $checkIn->diffInHours($checkOut);

        $attendance->update([
            'work_schedule_id' => $request->work_schedule_id,
            'date' => $request->date,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'working_hours' => $workingHours,
        ]);

        return redirect()->back()->with('success', 'Chỉnh sửa chấm công thành công');
    }
    public function delete($id){
        $attendance = attendance::findOrFail($id);

        $attendance->delete();

        return redirect()->back()->with('success', 'Xóa chấm công thành công');
    }
    public function approve($id)
    {
        $attendance = attendance::find($id);
        if ($attendance) {
            $attendance->status = 1;
            $attendance->save();
            notification::create([
                'role_id' => 4,
                'employee_id' => $attendance->employee_id,
                'name' => "Chấm công",
                'url' => route('attendance.index.admin'),
                'description' => "Giờ làm việc ngày " .$attendance->date ." của bạn đã được phê duyệt",
            ]);
            return redirect()->route('attendance.index.admin')->with('success','phê duyệt thành công');
        } else return redirect()->route('attendance.index.admin')->with('error','phê duyệt không thành công');
    }

    public function generateCheckinQrCode(Request $request)
    {
        $employeeId = Auth::user()->employee_id;
        $work_schedule = work_shift::orderBy('created_at')->get();

        return view('checkin.index', compact('work_schedule'));
    }

    public function checkin(Request $request)
    {
        $user = Auth::user();
        // $ip = $request->ip();

        // $checkIpWithBranch = Branch::where('id', Auth::user()->employee->branch_id)->where('address_ip', $ip)->first();

        // if(!$checkIpWithBranch){
        //     return redirect()->back()->with('error', 'Bạn không thể checkin vì chưa sử dụng mạng của công ty');
        // }

        $checkCheckIn = attendance::where('employee_id', $user->employee_id)->whereNull('check_out')->orderBy('id', 'desc')->first();

        if ($checkCheckIn){
            return redirect()->back()->with('error', 'Bạn đang check-in, vui lòng check-out trước khi check-in');
        }

        attendance::create([
            'employee_id' => $user->employee_id,
            'date' => now()->toDateString(),
            'work_schedule_id' => $request->work_schedule,
            'check_in' => now()->toTimeString(),
        ]);

        $message = 'Bạn đã checkin thành công!';
        $notification = 'success';
        return view('checkin.notification', compact('message', 'notification'));
    }

    public function checkout(){
        $employeeId = Auth::user()->employee_id;
        // $ip = request()->ip();

        // $checkIpWithBranch = Branch::where('id', Auth::user()->employee->branch_id)->where('address_ip', $ip)->first();

        // if(!$checkIpWithBranch){
        //     return redirect()->back()->with('error', 'Bạn không thể check-out vì chưa sử dụng mạng của công ty');
        // }

        $checkCheckIn = attendance::where('employee_id', $employeeId)->whereNull('check_out')->orderBy('id', 'desc')->first();

        if (!$checkCheckIn){
            $message = 'Bạn chưa check-in, vui lòng check-in trước khi check-out';
            $notification = 'error';
            return view('checkin.notification', compact('message', 'notification'));
        }

        $checkIn = new Carbon($checkCheckIn->check_in);
        $checkOut = Carbon::now();

        $workingHours = $checkIn->diffInHours($checkOut);
        $checkCheckIn->update([
            'check_out' => $checkOut->format("H:i:s"),
            'working_hours' => $workingHours,
        ]);

        $message = 'Bạn đã checkout thành công!';
        $notification = 'success';
        return view('checkin.notification', compact('message', 'notification'));
    }

    public function export(Request $request){
        return Excel::download(new AttendanceExport($request), 'attendance.xlsx');
    }
}
