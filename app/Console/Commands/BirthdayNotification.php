<?php

namespace App\Console\Commands;

use App\Mail\SendBirthdayNotification;
use App\Models\Employee;
use App\Models\notification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BirthdayNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:birthday-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now();
        $employees = Employee::whereMonth('date_of_birth', $today->month)
                             ->whereDay('date_of_birth', $today->day)
                             ->get();

        if ($employees->isEmpty()) {
            return;
        }

        $names = $employees->pluck('full_name')->toArray();

        if (count($names) > 1) {
            $description = 'Hôm nay là sinh nhật của nhân viên ' . implode(', ', array_slice($names, 0, -1)) . ' và ' . end($names);
        } else {
            $description = 'Hôm nay là sinh nhật của nhân viên ' . $names[0];
        }


        notification::create([
            'role_id' => 1,
            'name' => "Thông báo sinh nhật",
            'discription' => $description
        ]);

        $employeesReceive = Employee::with('user')->whereHas('user', function ($query) {
            $query->where('role_id', 4);
        })->get();

        foreach ($employeesReceive as $employee) {
            notification::create([
                'role_id' => 4,
                'emloyee_id' => $employee->id,
                'name' => "Thông báo sinh nhật",
                'discription' => $description
            ]);
        }

        Mail::to(config('notify.email'))->send(new SendBirthdayNotification($employees));

        $this->info('Thông báo sinh nhật thành công');
    }
}
