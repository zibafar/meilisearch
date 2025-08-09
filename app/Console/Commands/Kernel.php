<?php

namespace App\Console\Commands;

use App\Console\Commands\Meilisearch\Flush;
use App\Console\Commands\Meilisearch\Rebuild;
use App\Console\Commands\Meilisearch\Setup;
use App\Console\Commands\Meilisearch\SyncRecordsToSearch;
use App\Models\Config;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;

class Kernel extends ConsoleKernel
{

    protected $commands=[
        Setup::class,
        Flush::class,
        Rebuild::class,
        FilterFields::class,
        SortFields::class,
        SyncRecordsToSearch::class
    ];
    protected array $signatures = [
        SyncRecordsToSearch::SIGN,
        Rebuild::SIGN,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        (env('APP_ENV') == 'local') && Cache::forget("schedule");
        $frequency = Cache::remember("schedule", now()->minutes(3)->seconds(30),
            function () {
                //$default = '*/5 * * * *'; //->everyFiveMinutes()
                $default = '* * * * *';//->hourly()   minute=0

                $frequency=[];
                foreach ($this->signatures as $signature) {
                    $frequency[$signature] =
                        Config::get('schedule', $signature, 'frequency', $default, true);
                }

                return $frequency;
            }
        );

        foreach ($this->signatures as $signature) {
            $schedule->command($signature)
                ->cron($frequency[$signature])
                ->withoutOverlapping();
        }
//        $schedule->command('add:records', ['index' => 'post'])->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
