<?php

namespace App\Http\Controllers;

use App\Models\role;
use App\Models\RolePagePermission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(){
        $roles = role::all();

        $role_id = request('role_id') ?? $roles->first()->id;

        $role_page_permissions = RolePagePermission::with('pagePermission.page','pagePermission.permission', 'role')->where('role_id', $role_id)->get();

        return view('permission.index', compact('role_page_permissions', 'roles'));
    }

    public function changeStatus($id){
        try {
            $role_page_permissions = RolePagePermission::find($id);

            if (!$role_page_permissions){
                return response()->json([
                    'message' => 'không tìm thấy vai trò'
                ], 404);
            }

            $role_page_permissions->update([
                'status' => $role_page_permissions->status == 0 ? 1 : 0,
            ]);

            return response()->json(['message' => 'Thay đổi trạng thái thành công'], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Có lỗi xảy ra ' .$e->getMessage()
            ], 500);
        }
    }
}
