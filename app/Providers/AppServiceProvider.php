<?php

namespace App\Providers;

use App\Console\Commands\BirthdayNotification;
use App\Console\Commands\LateArrivalViolationInWeek;
use App\Console\Commands\SalaryCalculation;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->commands([
            SalaryCalculation::class,
            BirthdayNotification::class,
            LateArrivalViolationInWeek::class
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Schedule $schedule): void
    {
        $schedule->command(SalaryCalculation::class)->lastDayOfMonth('19:00');

        $schedule->command(BirthdayNotification::class)->dailyAt('07:00');

        $schedule->command(LateArrivalViolationInWeek::class)->weeklyOn(0, '19:02');
    }
}
