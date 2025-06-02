<?php

namespace App\Http\Controllers;

use App\Models\attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('home.admin');
        }

        return view('login.index');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials, true)) {
            if (Auth::user()->employee->end_date) {
                Auth::logout();
                return redirect()->back()->with(['errorLogin' => 'Tên đăng nhập hoặc mật khẩu sai']);
            }

            Cache::forever('user_permissions_' . Auth::user()->id, Auth::user()->role->pagePermissions()->where('status', 1)->get(['permission_id', 'page_id'])->toArray());

            return redirect()->route('home.admin')->with('success', 'Đăng nhập thành công');
        }

        return redirect()->back()->with(['errorLogin' => 'Tên đăng nhập hoặc mật khẩu sai']);
    }
    public function logout()
    {
        if (Auth::check()) {
            Cache::forget('user_permissions_' . Auth::user()->id);
            Auth::logout();
        }
        return redirect()->route('login.index');
    }

    public function download()
    {
        return response()->download(storage_path('app/employee-must-read.pdf'));
    }
}
