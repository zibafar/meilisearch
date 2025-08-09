<?php

namespace App\Console\Commands\Meilisearch;

use App\Models\Moodle\Meili\MeiliForum;
use App\Models\Moodle\Meili\MeiliVideo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Meilisearch\Exceptions\ApiException;

class Setup extends __Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:setup {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'execute meilisearch commands for all indexes:  [forum,video]';

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
     * @return int
     */
    public function handle()
    {
        $this->logInfo();

        try {
        $models=$this->getModels();

        foreach ($models as $model) {

            $this->runCommands($model);
        }
        } catch (ApiException $exception) {
            $this->report_error($exception);

            return self::FAILURE;
        }

        return 0;

    }

    /**
     * @param string $model
     * @return void
     */
    public function runCommands(string $model): void
    {
        $index = $model::INDEX;
        $this->info(
            string: "importing records  for [$model]",
        );
        //import commands
        Artisan::call('scout:import', ['model' => $model, '-v']);
        //set search fields

        $this->info(
            string: "Updating filterable attributes for [$model]  on index [$index]",
        );
        Artisan::call('scout:sorts', ['index' => $index]);
        //filter fields
        $this->info(
            string: "Updating sortable attributes for [$model]  on index [$index]",
        );
        Artisan::call('scout:filters', ['index' => $index]);

        $this->line("<bg=blue;options=bold>import and define search and filter fields for this mode:</>\n".
            "<bg=yellow;options=bold;fg=black> scout:import and scout:sorts and scout:filters have been done successfully,model is </> <fg=Cyan> {$model}</> \n");


    }


}
