<?php

namespace App\Http\Controllers;

use App\Mail\SendSupportComplaint;
use App\Models\notification;
use App\Models\Support_complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Support_complaintController extends Controller
{
    public function index(){
        $user = Auth::user();
        $complaint = Support_complaint::with('employee.branch');

        if($user->role_id == 4){
            $complaint->where('employee_id', $user->employee_id);
        }
        if($user->role_id == 3){
            $complaint->whereHas('employee', function ($query) use ($user){
                $query->where('branch_id', $user->employee->branch_id);
            });
        }

        $complaint = $complaint->OrderBy('id', 'desc')->paginate(10);

        return view('complaint.index', compact('complaint'));
    }

    public function create(Request $request){
        $user = Auth::user();
        $complaint = Support_complaint::create([
            'employee_id' => $user->employee_id,
            'complaint_type' => $request->complaint_type,
            'description' => $request->description,
            'status' => 0,
        ]);

        if ($complaint){
            $complaint = Support_complaint::with('employee' ,'employee.branch')->find($complaint->id);
            Mail::to(config('notify.email'))->send(new SendSupportComplaint($complaint));
            notification::create([
                'role_id' => 1,
                'name' => "Yêu cầu hỗ trợ / khiếu nại",
                'discription' => "Nhân viên ".$complaint->employee->full_name ." chi nhánh " .$complaint->employee->branch->branch_name
                 ." đã gửi yêu cầu hỗ trợ khiếu nại về " .$complaint->complaint_type,
            ]);

            return redirect()->back();
        }

        return redirect()->back();
    }

    public function approve($id){
        $complaint = Support_complaint::find($id);
        if($complaint){
            $complaint->update([
                'status' => 1,
            ]);
            notification::create([
                'role_id' => 4,
                'employee_id' => $complaint->employee_id,
                'name' => "Yêu cầu hỗ trợ / khiếu nại",
                'discription' => "Yêu cầu hỗ trợ / khiếu nại của bạn đã được phê duyệt",
            ]);

            return redirect()->back();
        }
    }
}
