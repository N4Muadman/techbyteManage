<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\work_schedule;
use App\Models\work_shift;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class work_scheduleController extends Controller
{
    public $log = [];

    public function index(Request $request){
        $user = Auth::user();
        $work_schedule = work_schedule::with('employee', 'work_shift')
        ->select(
            'date',
            'work_shift_id',
            DB::raw("COUNT(employee_id) as total_employee"),
            DB::raw("GROUP_CONCAT(employee_id ORDER BY employee_id ASC SEPARATOR ', ') as employee_list"),
            DB::raw("GROUP_CONCAT(branch_id ORDER BY branch_id ASC SEPARATOR ', ') as branch_list"),
        );
        if($request->work_shift){
            $work_schedule->where('work_shift_id', $request->work_shift);
        }
        if($request->date){
            $work_schedule->where('date', $request->date);
        }
        if($request->branch){
            $work_schedule->where('branch_id', $request->branch);
        }

        $employee = Employee::with('user')->whereHas('user', function ($query){
            $query->where('role_id', 4);
        });
        if($user->role_id == 3){
            $work_schedule->where('branch_id', $user->employee->branch_id);
            $employee->where('branch_id', $user->employee->branch_id);
        }
        if($user->role_id == 4){
            $work_schedule->where('employee_id', $user->employee_id);
        }

        $work_schedule = $work_schedule->groupBy('date', 'work_shift_id')
        ->orderBy('date', 'desc')
        ->paginate(10);
        $employee = $employee->get();
        $branch = Branch::where('status', 1)->get();
        $work_shifts = work_shift::all();

        return view('work_schedule.index', compact('work_schedule','work_shifts' ,'branch', 'employee'));
    }
    public function create(Request $request){
        $work_date = $request->work_date;
        $work_shift_id = $request->work_shift_id;
        $employee_ids = $request->employee_ids;
        $branch_id = $request->branch_id ?? Auth::user()->employee->branch_id;

        if(empty($employee_ids)){
            return redirect()->back()->with('error', 'Thêm lịch làm việc không thành công, bạn chưa chọn nhân viên');
        }
        // Tạo lịch làm việc cho từng nhân viên
        foreach ($employee_ids as $employee_id) {
            work_schedule::create([
                'date' => $work_date,
                'work_shift_id' => $work_shift_id,
                'employee_id' => $employee_id,
                'branch_id' => $branch_id,
            ]);
        }
        return redirect()->back()->with('success', 'Lịch làm việc đã được tạo thành công.');
    }

    public function employeeOfBranch($id){
        $employee = Employee::with('user')->where('branch_id', $id)->whereHas('user', function ($query){
            $query->where('role_id', 4);
        })->whereNull('end_date')->get();

        if($employee->isEmpty()){
            return response()->json([
                'status' => 403,
                'message' => 'không có nhân viên nào trong chi nhánh này'
            ]);
        }

        return response()->json([
            'status' => 200,
            'employee' => $employee
        ]);
    }

    public function importFile(Request $request){
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ],[
            'file.required' => 'Vui lòng chọn file trước',
            'file.mimes' => 'Chỉ cho phép đuôi xls, xlsx',
        ]);

        if($request->hasFile('file')){
            $work_schedules = Excel::toArray([], $request->file('file'));
            $addedCount = 0;

            foreach($work_schedules[0] as $key => $it){
                $getId = $this->checkDataInFile($key ,$it);
                if(count($it) < 4 || !$getId){
                    continue;
                }

                $date = $this->validateAndFormatDate($it, $key);
                if (!$date) {
                    continue;
                }

                work_schedule::create([
                    'branch_id' => $getId[0],
                    'employee_id' => $getId[1],
                    'work_shift_id' => $getId[2],
                    'date' => $date,
                ]);

                $addedCount++;
            }

            if ($addedCount == 0 && !empty($this->log)) {
                return redirect()->back()->with([
                    'error' => 'Không có lịch làm việc nào được thêm. Tất cả dữ liệu đều có lỗi.',
                    'log' => $this->log
                ]);
            }

            if(!empty($this->log) ){
                return redirect()->back()->with([
                    'success' => 'Một số lịch làm việc đã được thêm nhưng có lỗi trong quá trình xử lý.',
                    'log' => $this->log
                ]);
            }
            return redirect()->back()->with('success', 'Thêm lịch làm việc thành công');
        }
        return redirect()->back()->with('error', 'Chưa có file nào được chọn.');
    }


    public function checkDataInFile($key , $it){
        $branchId = Branch::select('id')->where('branch_name', trim($it[0]))->first();
        if (!$branchId) {
            $this->log[] = 'Chi nhánh "' . $it[0] . '" ở dòng ' . ($key + 1) . ' không hợp lệ.';
            return false;
        }

        $employeeId = Employee::with('user')->where('full_name', trim($it[1]))
        ->where('branch_id', $branchId->id)
        ->whereHas('user', function ($query){
            $query->where('role_id', 4);
        })->first();
        if (!$employeeId) {
            $this->log[] = 'Nhân viên "' . $it[1] . '" ở dòng ' . ($key + 1) . ' không hợp lệ.';
            return false;
        }

        $work_shiftId = work_shift::select('id')->where('name', trim($it[2]))->first();
        if (!$work_shiftId) {
            $this->log[] = 'Ca làm "' . $it[2] . '" ở dòng ' . ($key + 1) . ' không hợp lệ.';
            return false;
        }

        return [$branchId->id, $employeeId->id, $work_shiftId->id];
    }
    public function validateAndFormatDate($rawDate, $key)
    {
        try {
            if (is_numeric($rawDate[3])) {
                $date = Carbon::parse('1899-12-30')->addDays((int) trim($rawDate[3]));
            } else {
                $date = Carbon::createFromFormat('d-m-Y', trim($rawDate[3]));
            }
            return $date;

        } catch (\Exception $e) {
            $this->log[] = 'Ngày "' . $rawDate[3] . '" ở dòng ' . ($key + 1) . ' không hợp lệ.';
            return false;
        }
    }
    public function edit(Request $request)
    {
        try {
            $work_schedule = work_schedule::with('employee', 'work_shift')->where('employee_id', $request->employee_id)
                ->where('work_shift_id', $request->work_shift_id)
                ->where('date', $request->work_date)
                ->first();

            if($work_schedule){
                $work_shifts = work_shift::all();
                return view('work_schedule.edit', compact('work_schedule', 'work_shifts'));
            }
            return "Không tồn tại lịch làm việc của nhân viên";
        } catch (\Exception $e) {
            return "Có lỗi xảy ra!" .$e->getMessage();
        }
    }

    public function update(Request $request, $id){
        try{
            $workSchedule = work_schedule::findOrFail($id);

            $workSchedule->update([
                'date' => $request->work_date,
                'work_shift_id' => $request->work_shift_id,
            ]);

            return redirect()->route('workschedule.index.admin')->with('success', 'Sửa lịch làm việc thành công');
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return redirect()->route('workschedule.index.admin')->with('error', 'lịch làm việc không tồn tại');
        }
        catch (\Exception $e) {
            return redirect()->route('workschedule.index.admin')->with('error','Có lỗi xảy ra');
        }
    }

    public function getWorkScheduleByEmployee($id)
    {
        $workSchedules = work_schedule::with('work_shift')->where('employee_id', $id)->get();
        return response()->json([
            'message' => 'Danh sách ca làm việc',
            'workSchedules' => $workSchedules
        ], 200);
    }
}
