<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\salary;
use App\Models\WorkPerformance;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            ->select('a.employee_id')
            ->selectRaw('SUM(a.working_hours) AS total_hours_worked')
            ->selectRaw('COUNT(CASE WHEN TIME(a.check_in) > w.start_time THEN 1 END) AS total_late_arrivals')
            ->selectRaw('SUM(CASE WHEN TIME(a.check_in) > w.start_time THEN TIME_TO_SEC(TIMEDIFF(a.check_in, w.start_time)) / 3600 ELSE 0 END) AS total_late_hours')
            ->whereMonth('a.date', $currentMonth)
            ->whereYear('a.date', $currentYear)
            ->groupBy('a.employee_id')
            ->get();

        $employeeIds = $attendances->pluck('employee_id')->toArray();
        $employees = Employee::whereIn('id', $employeeIds)->get()->keyBy('id');

        try {
            $salaries = [];
            $performances = [];
            foreach ($attendances as $attendance) {
                $employee = $employees->get($attendance->employee_id);

                if (!$employee || !$employee->base_salary) {
                    continue;
                }

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
                    'total_project' => 0,
                    'total_revenue' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
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
