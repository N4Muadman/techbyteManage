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
        $results = attendance::select(
                DB::raw("DATE_FORMAT(attendance.date, '%Y-%m') as month"),
                'n.id as employee_id',
                'n.full_name',
                DB::raw("SUM(attendance.working_hours) as total_working_hours"),
            )
            ->join('employee as n', 'attendance.employee_id', '=', 'n.id')
            ->where('attendance.status', 1)
            ->groupBy(DB::raw("DATE_FORMAT(attendance.date, '%Y-%m'), n.full_name, n.id"));
            if($this->request->full_name){
                $results->where('n.full_name', 'like', '%' . $this->request->full_name . '%');
            }
            if($user->role_id == 4){
                $results->where('n.id', $user->employee_id);
            }
            if($this->request->position){
                $results->where('n.position', 'like', '%' . $this->request->position . '%');
            }
            if($this->request->branch){
                $results->where('n.branch_id', $this->request->branch);
            }
            if($this->request->date){
                $month = Carbon::createFromFormat('Y-m-d', $this->request->date)->endOfMonth()->format('Y-m');
                $results->where(DB::raw("DATE_FORMAT(attendance.date, '%Y-%m')"), $month);
            }
        $results = $results->orderBy('month', 'desc')->orderBy('full_name')->paginate(10);
        $branches = Employee::SELECT('employee.id','employee.position', 'branch.branch_name')->join('branch', 'branch.id', 'employee.branch_id')->get();
        $salary = salary::select("salary.*",
            DB::raw("DATE_FORMAT(salary_date, '%Y-%m') as month"))
            ->get();

        $branch = Branch::where('status', 1)->get();
        return view('salary.export', compact('results', 'salary', 'branches', 'branch'));
    }
}
