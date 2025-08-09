<?php

namespace App\Console\Commands\Meilisearch;

use App\Models\Config;
use Carbon\Carbon;
use Http\Client\Exception;
use Illuminate\Support\Facades\Artisan;
use Meilisearch\Contracts\IndexesQuery;
use Meilisearch\Exceptions\ApiException;
use Meilisearch\Exceptions\CommunicationException;

class SyncRecordsToSearch extends __Command
{
    public const SIGN = 'sync:records';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = self::SIGN . ' {index? : The index you want to work with.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync  searchable fields with index in meilisearch';

    /**
     * Create a new command instance.
     *
     * @return void
     */


    /**
     * Execute the console command.
     *
     * @return int|string
     */
    public function handle(): int|string
    {
        $this->logInfo();

        $index = $this->argument(key: 'index');
        $indexes = $this->ConvertToArr($index);

        foreach ($indexes as $index) {
            try {
                $this->exec($index);
            } catch
            (ApiException $exception) {
                $this->report_error($exception);
                return self::FAILURE;
            }
        }
        return 0;
    }

    public function exec(string $index): int
    {
        $model = $this->getModelByIndex($index);
        if (empty($model)) {
            $this->error(string: "error: model not found for this index: {$index}");
            return self::FAILURE;
        }

        $modified_field = $this->getModifiedFieldByIndex($index);
        if (empty($modified_field)) {
            $this->error(string: "modified filed not found for this index: {$index}");
            return self::FAILURE;
        }

        try {
            $date = $this->getDateForModified($index);

            $this->info(string: "adding records  for [$model] on index [$index] from [$date]");
            $query = $this->getFilter($model, $date, $modified_field);
            $query->searchable();
            $deleted_count = $this->deletedIds($model, $index);
            $query_count = $query->count();
            $this->line(
                "<fg=Cyan>{$query_count}</> <bg=blue;options=bold> records has been added successfully And </>\n" .
                "<fg=Cyan>{$deleted_count}</> <bg=blue;options=bold> records has been deleted successfully </>\n"
            );
            $this->info(
                string: "Updating filterable attributes for [$model]  on index [$index]",
            );
            Artisan::call('scout:sorts', ['index' => $index]);
            //filter fields
            $this->info(
                string: "Updating sortable attributes for [$model]  on index [$index]",
            );
            Artisan::call('scout:filters', ['index' => $index]);

            $this->info(string: "import and define search and filter fields for this model :
             {$model} scout:sorts and scout:filters have been done successfully");


            $this->setDateForModified($index);
        } catch (Exception $e) {
            $this->error(string: "Exception: {$e->getMessage()}");
            return self::FAILURE;
        }

        return self::SUCCESS;
    }


    private function deletedIds($model, $index): int
    {
        //in meilisearch_ids
        $s_ids = $this->getIdsFromIndex($index);
        //database_ids
        $m_ids = app($model)::pluck('id')->toArray();

        $deleted = array_diff($s_ids, $m_ids);

        //delete
        $p = app($model);
        foreach ($deleted as $id) {
            $p->id = $id;
            $p->unsearchable();
        }

        return count($deleted);
    }

    /**
     * @param $index
     * @return array
     */
    public function getIdsFromIndex($index): array
    {
        $s_ids = $this->client->index($index)->search("", [
            'attributesToRetrieve' => ['id']
        ])->getHits();
        return array_column($s_ids, 'id');
    }

    /**
     * @param string $model
     * @param Carbon $date
     * @param string $modified_field
     * @return mixed
     */
    public function getFilter(string $model, Carbon $date, string $modified_field)
    {
        return app($model)::where('course.timemodified','>=',$date->timestamp)->get()->where($modified_field ,'>=' ,$date);
    }
    /**
     * @return Carbon
     */
    public function getDateForModified($index): Carbon
    {
        $last_run = Config::getLastRun('schedule', self::SIGN . '-' . $index);
        if ($last_run){
            $last_run = new Carbon($last_run);
        }

        //$last_run = Carbon::now()->addDays(-360);
        return $last_run ?? Carbon::yesterday()->startOfDay();
    }
    public function setDateForModified($index)
    {
        $last_run =  Carbon::now()->addSeconds(-5);
        Config::set(Config::TBL, 'schedule', self::SIGN . '-' . $index, $last_run);
    }


}
