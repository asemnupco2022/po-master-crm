<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {


        //new import schedulers
        $schedule->command('lbs:import-sap-po')->dailyAt('01:00'); //SAP Import and copy files
        $schedule->command('lbs:Sap-master-sync-tmp')->dailyAt('05:00'); //SAP Import and copy files
        $schedule->command('lbs:sync-sap-filter')->dailyAt('06:00'); //SAP filter sync
        $schedule->command('lbs:full-sap-automation')->dailyAt('06:30'); //SAP filter sync

         //Run Notification Scheduler
        $schedule->command('lbs:notification-scheduler')->everyMinute(); //Execute Notification Scheduler for manual-automation
        //  $schedule->command('lbs:po-scheduler-filter')->daily(); //filter data for sending notification

        //  $schedule->command('queue:work')->everyFourMinutes();
//         $schedule->command('lbs:po-scheduler-filter')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
