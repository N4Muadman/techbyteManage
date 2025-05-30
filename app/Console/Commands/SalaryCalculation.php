<?php

namespace App\Console\Commands;

use App\Mail\LateArrivalViolationNotice;
use App\Models\Employee;
use App\Models\Project;
use App\Models\salary;
use App\Models\WorkPerformance;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SalaryCalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:salary-calculation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tính lương và hiệu suất làm việc của nhân viên';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $attendances = DB::table('attendance as a')
            ->join('work_shift as w', 'a.work_schedule_id', '=', 'w.id')
            ->select(
                'a.employee_id',
                DB::raw('SUM(a.working_hours) AS total_hours_worked'),
                DB::raw('COUNT(CASE WHEN TIME(a.check_in) > w.start_time THEN 1 END) AS total_late_arrivals'),
                DB::raw('SUM(CASE WHEN TIME(a.check_in) > w.start_time THEN TIME_TO_SEC(TIMEDIFF(a.check_in, w.start_time)) / 3600 ELSE 0 END) AS total_late_hours')
            )
            ->whereMonth('a.date', $currentMonth)
            ->whereYear('a.date', $currentYear)
            ->groupBy('a.employee_id')
            ->get();

        $employeeIds = $attendances->pluck('employee_id')->toArray();
        $employees = Employee::with('user')->whereIn('id', $employeeIds)->whereNull('end_date')->get()->keyBy('id');

        $projects = Project::with(['members', 'tasks'])
            ->whereMonth('end_date', $currentMonth)
            ->whereYear('end_date', $currentYear)
            ->get()
            ->filter(function ($project) {
                return $project->progress == 100;
            });

        try {
            $salaries = [];
            $performances = [];
            foreach ($attendances as $attendance) {
                $employee = $employees->get($attendance->employee_id);

                if (!$employee || !$employee->base_salary) {
                    continue;
                }

                $projectOfEmployee = $projects->filter(function ($project) use ($employee) {
                    return $project->leader_id == $employee->user->id ||
                        $project->members->contains('user_id', $employee->user->id);
                });

                $salaries[] = [
                    'employee_id' => $employee->id,
                    'base_salary' => $employee->base_salary,
                    'allowance' => 0,
                    'bonus' => 0,
                    'deduction' => 0,
                    'total_work' => $attendance->total_hours_worked,
                    'total_late_arrivals' => $attendance->total_late_arrivals,
                    'total_late_hours' => $attendance->total_late_hours,
                    'description' => null,
                    'salary_date' => Carbon::now()->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                $performances[] = [
                    'employee_id' => $employee->id,
                    'total_work' => $attendance->total_hours_worked,
                    'total_late_arrivals' => $attendance->total_late_arrivals,
                    'total_late_hours' => $attendance->total_late_hours,
                    'total_project' => $projectOfEmployee->count(),
                    'total_revenue' => $projectOfEmployee->sum('total_cost'),
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                if ($attendance->total_late_hours > 1 && !empty($employee->email)) {
                    Mail::to($employee->email)->send(new LateArrivalViolationNotice($employee, $attendance->total_late_hours, $attendance->total_late_arrivals, 'Tháng này'));
                }
            }

            salary::insert($salaries);
            WorkPerformance::insert($performances);


            Log::info('Tình lương và hiệu suất làm việc cho nhân viên thành công');
            $this->info('Tính lương và hiệu suất làm việc cho nhân viên thành công');
        } catch (\Exception $e) {
            Log::error('Salary creation failed: ' . $e->getMessage());
        }
    }
}
