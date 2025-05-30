<?php

namespace App\Http\Controllers;

use App\Models\attendance;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\employee_performance_reviews;
use App\Models\WorkPerformance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class evaluationAndRewardsController extends Controller
{
    public function index(Request $request)
    {
        $startDate = Carbon::createFromFormat('Y-m', $request->start_month ?? now()->format('Y-m'))->startOfMonth()->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m', $request->end_month ?? now()->format('Y-m'))->endOfMonth()->endOfDay();
        $performance = WorkPerformance::with('employee', 'performance_review')->whereBetween('created_at', [$startDate, $endDate]);

        if (Auth::user()->role_id != 1) {
            $performance->where('employee_id', Auth::user()->employee_id);
        }

        if ($request->full_name) {
            $performance->whereHas('employee', function ($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->full_name . '%');
            });
        }

        if ($request->position) {
            $performance->whereHas('employee', function ($query) use ($request) {
                $query->where('position', 'like', '%' . $request->position . '%');
            });
        }

        if ($request->branch) {
            $performance->whereHas('employee', function ($query) use ($request) {
                $query->where('branch_id', $request->branch);
            });
        }

        $performance = $performance->orderBy('created_at', 'desc')->paginate(10);
        $branch = Branch::where('status', 1)->get();
        return view('evaluation.index', compact('performance', 'branch'));
    }
    
    public function create(Request $request)
    {
        $work_performance = WorkPerformance::find($request->work_performance_id);

        if (!$work_performance) {
            return redirect()->back()->with('error', 'Hiệu suất làm việc không tồn tại');
        }

        if ($request->attendance_score && $request->quality_score) {
            try {
                DB::beginTransaction();

                $scores = [
                    $request->attendance_score,
                    $request->quality_score,
                    $request->productivity_score,
                    $request->problem_solving_score,
                ];

                $overall_score = array_sum($scores) / 4;

                employee_performance_reviews::create([
                    "work_performance_id" => $work_performance->id,
                    'date' => now()->format('Y-m-d'),
                    "attendance_score" => $request->attendance_score ?? 0,
                    "quality_score" => $request->quality_score ?? 0,
                    "productivity_score" => $request->productivity_score ?? 0,
                    "problem_solving_score" => $request->problem_solving_score ?? 0,
                    "overall_score" => $overall_score,
                    "evaluation_result" => $request->evaluation_result,
                    "reward" => $request->reward,
                ]);

                DB::commit();
                return redirect()->back()->with('success', 'Cập nhật và thêm mới đánh giá thành công');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Cập nhật thành công nhưng thêm mới đánh giá thất bại: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Có lỗi xảy ra');
    }

    public function update(Request $request, $id)
    {
        $work_performance = WorkPerformance::find($id);

        if (!$work_performance) {
            return redirect()->back()->with('error', 'Hiệu suất làm việc không tồn tại');
        }

        if ($work_performance->performance_review) {
            try {
                DB::beginTransaction();

                $scores = [
                    $request->attendance_score,
                    $request->quality_score,
                    $request->productivity_score,
                    $request->problem_solving_score,
                ];

                $overall_score = array_sum($scores) / count(array_filter($scores, fn($score) => $score !== null));

                $work_performance->performance_review->update([
                    "attendance_score" => $request->attendance_score,
                    "quality_score" => $request->quality_score,
                    "productivity_score" => $request->productivity_score,
                    "problem_solving_score" => $request->problem_solving_score,
                    "overall_score" => $overall_score,
                    "evaluation_result" => $request->evaluation_result,
                    "reward" => $request->reward,
                ]);

                DB::commit();
                return redirect()->back()->with('success', 'Cập nhật đánh giá thành công');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Cập nhật thành công nhưng thêm mới đánh giá thất bại: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Cập nhật hiệu suất thành công');
    }
}
