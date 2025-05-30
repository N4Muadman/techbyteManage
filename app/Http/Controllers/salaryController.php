<?php

namespace App\Http\Controllers;

use App\Exports\salaryExport;
use App\Http\Controllers\Controller;
use App\Models\attendance;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\salary;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class salaryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $salaryQuery = salary::with('employee.branch');

        $startDate = Carbon::createFromFormat('Y-m', $request->start_month ?? now()->format('Y-m'))->startOfMonth()->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m', $request->end_month ?? now()->format('Y-m'))->endOfMonth()->endOfDay();

        $salaryQuery->whereBetween('salary_date', [$startDate, $endDate]);

        if ($user->role_id != 1) {
            $salaryQuery->where('employee_id', $user->employee_id);
        }
        if ($request->full_name) {
            $salaryQuery->whereHas('employee', function ($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->full_name . '%');
            });
        }
        if ($request->position) {
            $salaryQuery->whereHas('employee', function ($query) use ($request) {
                $query->where('position', 'like', '%' . $request->position . '%');
            });
        }
        if ($request->branch) {
            $salaryQuery->whereHas('employee', function ($query) use ($request) {
                $query->where('branch_id', $request->branch);
            });
        }

        $salaries = $salaryQuery->orderBy('created_at', 'desc')->paginate(10);
        $branch = Branch::where('status', 1)->get();
        return view('salary.index', compact('salaries', 'branch'));
    }
    public function create(Request $request)
    {
        $employee_id = $request->employee_id;
        $allowance = intval(preg_replace('/\D/', '', $request->allowance));
        $bonus = intval(preg_replace('/\D/', '', $request->bonus));
        $deductions = intval(preg_replace('/\D/', '', $request->deductions));
        $salary_date = $request->salary_date;
        $formatted_date = $salary_date
            ? Carbon::createFromFormat('Y-m', $salary_date)->endOfMonth()->format('Y-m-d')
            : Carbon::now()->format('Y-m-d');
        $salary = salary::create([
            'employee_id' => $employee_id,
            'allowance' => $allowance,
            'bonus' => $bonus,
            'deductions' => $deductions,
            'salary_date' => $formatted_date,
            'description' => $request->description
        ]);
        if ($salary) {
            return redirect()->route('salary.index.admin')->with(['success', 'Tạo lương cho nhân viên thành công']);
        } else return redirect()->route('salary.index.admin')->with(['error', 'Tạo lương cho nhân viên không thành công']);
    }

    public function update(Request $request, $id)
    {
        $allowance = intval(preg_replace('/\D/', '', $request->allowance));
        $bonus = intval(preg_replace('/\D/', '', $request->bonus));
        $deduction = intval(preg_replace('/\D/', '', $request->deduction));
        $salary = salary::find($id);

        if (!$salary) {
            return redirect()->back()->with(['error', 'Không tìm thấy lương của nhân viên']);
        }

        $salary->update([
            'allowance' => $allowance,
            'bonus' => $bonus,
            'deduction' => $deduction,
            'description' => $request->description
        ]);

        return redirect()->back()->with(['success', 'Sửa lương cho nhân viên thành công']);
    }

    public function delete($id)
    {
        $salary = salary::find($id);

        if (!$salary) {
            return redirect()->back()->with(['error', 'Không tìm thấy lương của nhân viên']);
        }

        $salary->delete();
    }

    public function export(Request $request)
    {
        return Excel::download(new salaryExport($request), 'salary.xlsx');
    }
}
