<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\leave_request;
use App\Models\notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class leaveController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $leave = leave_request::with('employee');
        if($user->role_id == 4){
            $leave->where('employee_id', $user->employee_id);
        }
        if($request->full_name){
            $leave->whereHas('employee', function ($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->full_name . '%');
            });
        }
        if($request->position){
            $leave->whereHas('employee', function ($query) use ($request) {
                $query->where('position', 'like', '%' . $request->position . '%');
            });
        }
        if($request->date){
            $leave->where('start_date', $request->date)->orWhere('end_date', $request->date);
        }
        if($request->branch){
            $leave->whereHas('employee', function ($query) use ($request) {
                $query->where('branch_id', $request->branch);
            });
        }
        if ($request->leave_type){
            $leave->where('leave_type', $request->leave_type);
        }
        $leave = $leave->OrderBy('created_at', 'desc')->paginate(10);

        $branch = Branch::where('status', 1)->get();
        return view('leave.index', compact('leave', 'branch'));
    }
    public function approve($id){
        $leave = leave_request::with('employee.user')->find($id);
        if ($leave){
            $leave->leave_status = 1;
            $leave->save();
            notification::create([
                'role_id' => $leave->employee->user->role_id,
                'employee_id' => $leave->employee_id,
                'name' => "Nghỉ phép",
                'url' => route('leave.index.admin'),
                'discription' => "Nghỉ phép của bạn đã được phê duyệt,Từ ngày: " .$leave->start_date ." đến ngày: ".$leave->end_date,
            ]);
            return redirect()->route('leave.index.admin')->with(['success' => 'Phê duyệt nghỉ phép thành công']);
        }

        else return redirect()->route('leave.index.admin')->with(['error' => 'Có lỗi xảy ra']);
    }

    public function refuse($id){
        $leave = leave_request::with('employee.user')->find($id);
        if ($leave){
            $leave->leave_status = 2;
            $leave->save();
            notification::create([
                'role_id' => $leave->employee->user->role_id,
                'employee_id' => $leave->employee_id,
                'name' => "Nghỉ phép",
                'url' => route('leave.index.admin'),
                'discription' => "Nghỉ phép của bạn đã bị từ chối",
            ]);
            return redirect()->route('leave.index.admin')->with(['success' => 'Từ chối nghỉ phép thành công']);
        }

        else return redirect()->route('leave.index.admin')->with(['error' => 'Có lỗi xảy ra']);
    }
    public function create(Request $request){
        $user = Auth::user();
        leave_request::create([
            'employee_id' => $user->employee_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason_discription' => $request->reason_discription,
            'leave_status' => 0,
        ]);
        return redirect()->back()->with(['success' => 'Đăng kí nghỉ phép thành công']);
    }
    public function delete($id){
        $leave = leave_request::find($id);
        if($leave){
            $leave->delete();
            return redirect()->route('leave.index.admin')->with(['success' => 'Xóa nghỉ phép thành công']);
        }

        else return redirect()->route('leave.index.admin')->with(['error' => 'Có lỗi xảy ra']);
    }
}
