<?php

namespace App\Console\Commands;

use App\Mail\LateArrivalViolationNotice;
use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LateArrivalViolationInWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:late-arrival-violation-in-week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Thông báo vi phạm đi muộn trong tuần';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $attendances = DB::table('attendance as a')
            ->join('work_shift as w', 'a.work_schedule_id', '=', 'w.id')
            ->select('a.employee_id')
            ->selectRaw('SUM(a.working_hours) AS total_hours_worked')
            ->selectRaw('COUNT(CASE WHEN TIME(a.check_in) > w.start_time THEN 1 END) AS total_late_arrivals')
            ->selectRaw('SUM(CASE WHEN TIME(a.check_in) > w.start_time THEN TIME_TO_SEC(TIMEDIFF(a.check_in, w.start_time)) / 3600 ELSE 0 END) AS total_late_hours')
            ->whereBetween('date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->groupBy('a.employee_id')
            ->get();

        $employeeIds = $attendances->pluck('employee_id')->toArray();
        $employees = Employee::whereIn('id', $employeeIds)->get()->keyBy('id');

        try {
            foreach ($attendances as $attendance) {
                $employee = $employees->get($attendance->employee_id);

                if (!$employee){
                    continue;
                }

                if ($attendance->total_late_hours > 0.5) {
                    $type = 'Tuần này';
                    Mail::to($employee->email)->send(new LateArrivalViolationNotice($employee, $attendance->total_late_hours, $attendance->total_late_arrivals, $type));
                }
            }

            $this->info('Thông báo vi phạm đi muộn thành công');
        }catch (\Exception $e) {
            Log::error('Thông báo vi phạm đi muộn thất bại: ' . $e->getMessage());
        }
    }
}
