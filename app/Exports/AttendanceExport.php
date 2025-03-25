<?php

namespace App\Exports;

use App\Models\attendance;
use App\Models\Branch;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class AttendanceExport implements FromView
{
    public $request;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($request)
    {
        $this->request = $request;
    }
    public function view(): View {
        $user = Auth::user();
        $attendance = attendance::with(['employee.branch', 'work_schedule.work_shift']);
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
        if ($this->request->date) {
            $attendance->where('date', $this->request->date);
        }
        if ($this->request->full_name) {
            $attendance->whereHas('employee', function ($query) {
                $query->where('full_name', 'like', '%' . $this->request->full_name . '%');
            });
        }
        if ($this->request->position) {
            $attendance->whereHas('employee', function ($query) {
                $query->where('position', 'like', '%' . $this->request->position . '%');
            });
        }
        if ($this->request->branch) {
            $attendance->whereHas('employee.branch', function ($query) {
                $query->where('id', $this->request->branch);
            });
        }
        $attendance = $attendance->whereNotNull('check_out')->OrderBy('id', 'desc')->paginate(10);
        $branch = Branch::where('status', 1)->get();
        return view('attendance.export', compact('attendance', 'branch'));
    }
}
