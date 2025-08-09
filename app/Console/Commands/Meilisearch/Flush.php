<?php

namespace App\Console\Commands\Meilisearch;

use Illuminate\Support\Facades\Artisan;
use Meilisearch\Exceptions\ApiException;

class Flush extends __Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:flush   {index? : The index you want to work with.} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'meilisearch flush all [Forum search fields]';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return string
     */
    public function handle()
    {

        $this->logInfo();

        $index = $this->argument(key: 'index');
        $indexes = $this->ConvertToArr($index);

        foreach ($indexes as $index) {
            try {
                $model = $this->getModelByIndex($index);
                //flush commands
                Artisan::call('scout:flush',['model'=> $model]);
                $this->line("<bg=blue;options=bold>run command: meilisearch:flush</>\n".
                    "<bg=yellow;options=bold;fg=black> for this model </> <fg=Cyan> {$model}</> \n");
            } catch (ApiException $exception) {
                $this->report_error($exception);
                return self::FAILURE;
            }
        }
    }


}
