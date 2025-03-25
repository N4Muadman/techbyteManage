<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class branchController extends Controller
{
    public function index(){
        $branches = Branch::where('status', '!=', 3)->paginate(10);
        return view('branch.index', compact('branches'));
    }
    public function create(Request $request){
        $branch = Branch::create([
            'branch_name' => $request->branch_name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'address_ip' => $request->address_ip,
            'status' => $request->status,
        ]);
        if($branch){
            return redirect()->route('branch.index.admin')->with(['success' => 'Thêm chi nhánh thành công']);
        }
        else return redirect()->route('branch.index.admin')->with(['error' => 'Thêm chi nhánh không thành công']);
    }
    public function update(Request $request, $id){
        $branch = Branch::find($id);
        if($branch){
            $branch->update([
                'branch_name' => $request->branch_name,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'address_ip' => $request->address_ip,
                'status' => $request->status,
            ]);
            return redirect()->route('branch.index.admin')->with(['success' => 'Sửa nhánh thành công']);
        }
        else return redirect()->route('branch.index.admin')->with(['error' => 'Không tìm thấy chi nhánh']);
    }
    public function delete($id){
        $branch = Branch::find($id);
        if($branch){
            $branch->update([
                'status' => 3,
            ]);
            return redirect()->route('branch.index.admin')->with(['success' => 'Xóa nhánh thành công']);
        }
        else return redirect()->route('branch.index.admin')->with(['error' => 'Không tìm thấy chi nhánh']);
    }
}
