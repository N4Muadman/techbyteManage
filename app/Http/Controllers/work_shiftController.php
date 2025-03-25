<?php

namespace App\Http\Controllers;

use App\Models\work_shift;
use Illuminate\Http\Request;

class work_shiftController extends Controller
{
    public function index(){
        $work_shift = work_shift::all();
        return view('work_shift.index', compact('work_shift'));
    }

    public function create(Request $request){
        if($request->end_time <= $request->start_time){
            return redirect()->back()->with(['error' => 'Thời gian kết thúc ca phải lớn hơn thời gian bắt đầu']);
        }

        $work_shift = work_shift::create([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'name' => $request->name,
        ]);

        if($work_shift){
            return redirect()->back()->with(['success' => 'Thêm ca làm thành công']);
        }

        return redirect()->back()->with(['error' => 'Có lỗi xảy ra']);
    }
    public function update(Request $request, $id){
        $work_shift = work_shift::find($id);

        if($request->end_time <= $request->start_time){
            return redirect()->back()->with(['error' => 'Thời gian kết thúc ca phải lớn hơn thời gian bắt đầu']);
        }

        if($work_shift){
            $work_shift->update([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'name' => $request->name,
            ]);
            return redirect()->back()->with(['success' => 'Sửa ca làm thành công']);
        }

        return redirect()->back()->with(['error' => 'Có lỗi xảy ra']);
    }
    public function delete($id){
        $work_shift = work_shift::find($id);

        if($work_shift){
            $work_shift->delete();
            return redirect()->back()->with(['success' => 'Xóa ca làm thành công']);
        }

        return redirect()->back()->with(['error' => 'Có lỗi xảy ra']);
    }
}
