<?php

namespace App\Exports;

use App\Models\attendance;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\salary;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class salaryExport implements FromView
{
    public $request;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($request)
    {
        $this->request = $request;
    }
    public function view() :View
    {
        $user = Auth::user();

        $salaryQuery = salary::with('employee.branch');

        $startMonth = $this->request->start_month ?? now()->format('Y-m');
        $endMonth = $this->request->end_month ?? now()->format('Y-m');

        $startDate = Carbon::createFromFormat('Y-m', $startMonth)->startOfMonth()->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m', $endMonth)->endOfMonth()->endOfDay();

        $salaryQuery->whereBetween('salary_date', [$startDate, $endDate]);

        if ($user->role_id != 1) {
            $salaryQuery->where('employee_id', $user->employee_id);
        }
        if ($this->request->full_name) {
            $salaryQuery->whereHas('employee', function ($query) {
                $query->where('full_name', 'like', '%' . $this->request->full_name . '%');
            });
        }
        if ($this->request->position) {
            $salaryQuery->whereHas('employee', function ($query) {
                $query->where('position', 'like', '%' . $this->request->position . '%');
            });
        }
        if ($this->request->branch) {
            $salaryQuery->whereHas('employee', function ($query) {
                $query->where('branch_id', $this->request->branch);
            });
        }

        $salaries = $salaryQuery->orderBy('created_at', 'desc')->paginate(10);
        $branch = Branch::where('status', 1)->get();
        return view('salary.export', compact('salaries', 'branch'));
    }
}
