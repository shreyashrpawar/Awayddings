<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        'App\Console\Commands\EMIPayment',
        Commands\ApprovalExpirationCron::class,
        Commands\EventEMIPaymentCron::class,
        // Commands\EMIPayment::class,
        Commands\EventCancelBookingCron::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('bookings:cancel')->everyMinute();
        // $schedule->command('EMIPayment:cron')->everyMinute();
                 
        // $schedule->command('inspire')->hourly();

        $schedule->command('bookings:cancel')->dailyAt('12:00');
        $schedule->command('EMIPayment:cron')->dailyAt('11:40');
        $schedule->command('eventEMIPayment:cron')->dailyAt('12:00');
        $schedule->command('eventCancelBooking:cron')->dailyAt('12:00');
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
