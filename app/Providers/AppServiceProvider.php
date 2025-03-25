<?php

namespace App\Providers;

use App\Console\Commands\BirthdayNotification;
use App\Console\Commands\SalaryCalculation;
use Illuminate\Console\Scheduling\Schedule;
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
            BirthdayNotification::class
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Schedule $schedule): void
    {
        $schedule->command(SalaryCalculation::class)->everyMinute();
        $schedule->command(BirthdayNotification::class)->everyMinute();
    }
}
