<?php

namespace App\Http\Controllers;

use App\Models\PagePermission;
use App\Models\role;
use App\Models\RolePagePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = role::orderby('name', 'asc')->get();

        return view('role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $page_permissions = PagePermission::pluck('id');

            $role = role::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => 0
            ]);

            foreach ($page_permissions as $it) {
                RolePagePermission::create([
                    'role_id' => $role->id,
                    'page_permission_id' => $it,
                    'status' => 0
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Thêm mới vai trò thành công');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $role = role::find($id);

        if (!$role){
            return redirect()->back()->with('error', 'Vai trò không tồn tại');
        }

        $role->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Cập nhật vai trò thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = role::find($id);

        if (!$role){
            return redirect()->back()->with('error', 'Vai trò không tồn tại');
        }

        try {
            DB::beginTransaction();

            RolePagePermission::where('role_id', $role->id)->delete();

            $role->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Xoá vai trò thành công');
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra');
        }
    }
}
