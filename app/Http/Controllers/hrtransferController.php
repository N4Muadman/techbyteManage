<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\hrtransfer;
use App\Models\notification;
use Illuminate\Support\Facades\Auth;

class hrtransferController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $hrtransfer = hrtransfer::select('hrtransfer.*', 'employee.full_name', 'employee.position', 'old_branch.branch_name as old_branch_name', 'new_branch.branch_name as new_branch_name')
        ->join('employee', 'employee.id', 'hrtransfer.employee_id')
        ->join('branch as old_branch', 'old_branch.id', 'hrtransfer.old_branch')
        ->join('branch as new_branch', 'new_branch.id', 'hrtransfer.new_branch');
        if($request->full_name){
            $hrtransfer->where('full_name', 'like', '%' . $request->full_name . '%');
        }
        if($request->date){
            $hrtransfer->where('transfer_date', $request->date);
        }
        if($request->position){
            $hrtransfer->where('position', 'like', '%' . $request->position . '%');
        }
        if($request->branch){
            $hrtransfer->where('old_branch', $request->branch)->orwhere('new_branch', $request->branch);
        }

        $employee = null;

        if($user->role_id != 3){
            $employee = Employee::with('user', 'branch')->whereHas('user', function ($query){
                    $query->where('role_id', '4');
            })->where('id', '!=' , $user->employee_id)->whereNull('end_date')->get();
        }
        else{
            $hrtransfer->where('old_branch', $user->employee->branch_id)->orWhere('new_branch', $user->employee->branch_id);
        }
        $branch = Branch::where('status', 1)->get();

        $hrtransfer = $hrtransfer->OrderBy('hrtransfer.id', 'desc')->paginate(10);
        return view('hrtransfer.index', compact('hrtransfer', 'branch', 'employee'));
    }

    public function approve(Request $request, $id){
        $user = json_decode($request->employee[0]);
        $hrtransfer = hrtransfer::find($id);
        if ($hrtransfer){
            $hrtransfer->employee_id = $user->id;
            $hrtransfer->old_branch = $user->branch_id;
            $hrtransfer->transfer_date = $request->date;
            $hrtransfer->status = 1;
            $hrtransfer->save();

            $employee = Employee::find($hrtransfer->employee_id);
            $employee->update([
                'branch_id' => $hrtransfer->new_branch,
            ]);

            $hrtransfer = hrtransfer::with('employee', 'oldBranch', 'newBranch')->find($hrtransfer->id);
            notification::create([
                'role_id' => 3,
                'name' => "Điều động nhân sự",
                'branch_id' => $hrtransfer->old_branch,
                'url' => route('hrtransfer.index.admin'),
                'discription' => "Nhân viên ".$hrtransfer->employee->full_name ." của chi nhánh bạn đã được được điều động qua chi nhánh "
                .$hrtransfer->newBranch->branch_name ." để làm việc",
            ]);
            notification::create([
                'role_id' => 3,
                'name' => "Điều động nhân sự",
                'branch_id' => $hrtransfer->new_branch,
                'url' => route('hrtransfer.index.admin'),
                'discription' => "Yêu cầu điều động nhân sự của chi nhánh bạn đã được phê duyệt và nhân viên ".$hrtransfer->employee->full_name
                ." sẽ làm việc ở đây từ ngày " .$hrtransfer->transfer_date,
            ]);
            notification::create([
                'role_id' => 4,
                'name' => "Điều động nhân sự",
                'employee_id' => $hrtransfer->employee_id,
                'discription' => "Bạn đã được điều động qua chi nhánh ".$hrtransfer->newBranch->branch_name ." để làm việc",
            ]);
            return redirect()->route('hrtransfer.index.admin');
        }

        else return redirect()->route('hrtransfer.index.admin');
    }

    public function create(Request $request){
        $user = Auth::user();
        $hrtransfer = hrtransfer::create([
            'new_branch' => $user->employee->branch_id,
            'reason' => $request->reason,
            'status' => 0,
        ]);
        if ($hrtransfer) {
            $hrtransfer = hrtransfer::with('employee', 'oldBranch', 'newBranch')->find($hrtransfer->id);
            notification::create([
                'role_id' => 2,
                'name' => "Điều động nhân sự",
                'url' => route('hrtransfer.index.admin'),
                'discription' => "Chi nhánh ".$hrtransfer->newBranch->branch_name ." đã yêu cầu điều động nhận sự với lý do "
                .$hrtransfer->reason,
            ]);
            return redirect()->route('hrtransfer.index.admin');
        }
        else return redirect()->route('hrtransfer.index.admin');
    }
}
