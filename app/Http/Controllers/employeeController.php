<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\role;
use App\Models\salary;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class employeeController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::with(['branch', 'salary', 'user.role'])->where('id', '!=', Auth::user()->employee->id);
        if ($request->full_name) {
            $employee->where('full_name', 'like', '%' . $request->full_name . '%');
        }
        if ($request->position) {
            $employee->where('position', 'like', '%' . $request->position . '%');
        }
        if ($request->branch) {
            $employee->whereHas('branch', function ($query) use ($request) {
                $query->where('id', $request->branch); // Sử dụng 'like' để tìm kiếm theo tên chi nhánh
            });
        }
        if ($request->status) {
            $employee->whereNotNull('end_date');
        } else {
            $employee->whereNull('end_date');
        }
        $employee = $employee->OrderBy('created_at', 'desc')->paginate(10);

        $branch = Branch::where('status', 1)->get();

        $role = role::all();

        return view('employee.index', compact('employee', 'branch', 'role'));
    }
    public function create(Request $request)
    {
        $base_salary = intval(preg_replace('/\D/', '', $request->base_salary));
        $employee = Employee::create([
            "full_name" => $request->full_name,
            "date_of_birth" => $request->dateBirth,
            "gender" => $request->gender,
            "phone_number" => $request->sdt,
            "address" => $request->address,
            "email" => $request->email,
            "start_date" => $request->start_date,
            "position" => $request->position,
            "base_salary" => $base_salary,
            "branch_id" => $request->branch,
            "profile" => $request->profile
        ]);
        if ($employee) {
            User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role_id' => 4,
                'employee_id' => $employee->id,
            ]);
            return redirect()->route('employee.index.admin')->with(['success' => 'Thêm nhân viên thành công']);
        } else {
            return redirect()->route('employee.index.admin')->with(['error' => 'Thêm nhân viên không thành công!']);
        }
    }
    public function detail($id)
    {
        $employee = Employee::with(['branch', 'salary', 'user'])
            ->where('id', $id)->whereNull('end_date')->first();

        $salary = salary::where('employee_id', $id)->whereMonth('salary_date', date('m'))->whereYear('salary_date', date('Y'))->first();
        if ($employee) {
            return view('employee.detail', compact('employee', 'salary'));
        } else return abort(404);
    }
    public function edit(Request $request, $id)
    {
        $employee = Employee::with(['branch', 'salary', 'user'])
            ->where('id', $id)->whereNull('end_date')->first();

        $branch = Branch::where('status', 1)->get();
        if ($branch->isEmpty()) {
            return redirect()->route('employee.index.admin')->with(['error' => 'Chưa có chi nhánh nào trên hệ thống']);
        }

        if ($employee) {
            return view('employee.edit', compact('employee', 'branch'));
        } else return abort(404);
    }
    public function update(Request $request, $id)
    {
        $base_salary = intval(preg_replace('/\D/', '', $request->base_salary));
        $employee = Employee::with(['branch', 'salary'])
            ->where('id', $id)->whereNull('end_date')->first();

        if ($employee) {
            $employee->update([
                "full_name" => $request->full_name,
                "date_of_birth" => $request->dateBirth,
                "gender" => $request->gender,
                "phone_number" => $request->sdt,
                "address" => $request->address,
                "email" => $request->email,
                "position" => $request->position,
                "base_salary" => $base_salary,
                "branch_id" => $request->branch,
                "profile" => $request->profile,
            ]);

            $user = User::where('employee_id', $id)->first();
            $user->username = $request->username;
            if ($request->password) {
                $user->password = $request->password;
            }
            $user->save();

            return redirect()->route('employee.index.admin')->with(['success' => 'Chỉnh sửa nhân viên thành công']);
        } else {
            return redirect()->route('employee.index.admin')->with(['error' => 'Không tìm thấy nhân viên!']);
        }
    }
    public function delete($id)
    {
        $employee = Employee::with(['branch', 'salary'])
            ->where('id', $id)->first();
        if ($employee) {
            $employee->update([
                'end_date' => date('Y-m-d')
            ]);
            return redirect()->route('employee.index.admin')->with(['success' => 'Xóa nhân viên thành công']);
        } else {
            return redirect()->route('employee.index.admin')->with(['error' => 'Không tìm thấy nhân viên!']);
        }
    }
    public function profile()
    {
        $id = Auth::user()->employee_id;
        $employee = Employee::with(['branch', 'salary', 'user'])
            ->where('id', $id)->whereNull('end_date')->first();

        $salary = salary::where('employee_id', $id)->where('salary_date', date('Y-m-d'))->first();
        if (!$employee) {
            return abort(404);
        }
        return view('employee.profile', compact('employee', 'salary'));
    }
    public function empower(Request $request, $id)
    {
        $user = User::where('employee_id', $id)->first();
        if ($user) {
            $user->update([
                'role_id' => $request->role_id,
            ]);
            
            Cache::forget('user_permissions_' . $user->id);
            return redirect()->back()->with(['success' => 'Phân quyền nhân viên thành công']);
        }
        return redirect()->back()->with(['error' => 'Không tìm thấy nhân viên']);
    }

    public function formChangePassword()
    {
        return view('employee.change-password');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $findUser = User::find($user->id);

        if ($findUser && Hash::check($request->oldPassword, $findUser->password)) {
            $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ], [
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
                'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            ]);
            $findUser->password = Hash::make($request->password);
            $findUser->save();
            return redirect()->route('home.admin')->with(['success' => 'Thay đổi mật khẩu thành công']);
        }
        return redirect()->back()->with(['wrongPassword' => 'Mật khẩu cũ không đúng']);
    }
}
