<?php

namespace App\Console\Commands\Meilisearch;

use Illuminate\Support\Facades\Artisan;
use Meilisearch\Exceptions\ApiException;

class Rebuild extends __Command
{
    public const SIGN = 'meilisearch:rebuild';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = self::SIGN . ' {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'meilisearch rebuild all [Forum search fields]';

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

        //flush commands
        Artisan::call('meilisearch:flush');
        $this->line("<bg=blue;options=bold>run command: meilisearch:flush</>\n");

        //setup commands
        Artisan::call('meilisearch:setup');
        $this->line("<bg=blue;options=bold>run command: meilisearch:setup</>\n");
    }


}
