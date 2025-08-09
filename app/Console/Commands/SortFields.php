<?php

namespace App\Console\Commands;

use App\Console\Commands\Meilisearch\__Command;
use MeiliSearch\Client as MeiliSearchClient;
use Meilisearch\Exceptions\ApiException;

class SortFields extends __Command
{
    protected $signature = 'scout:sorts
        {index : The index you want to work with.}
    ';

    protected $description = 'Register filters for a search index.';

    public function handle(): int
    {
        $this->logInfo();

        $index = $this->argument(
            key: 'index',
        );

        $model = $this->getModelByIndex($index);
        try {
            $this->info(
                string: "Updating... filterable attributes for [$model] on index [$index]",
            );

            $this->client->index(
                uid: $index,
            )->updateSortableAttributes(
                sortableAttributes: $model::getSearchSortAttributes(),
            );
            $this->info(
                string: "Updating... stop words attributes for [$model] on index [$index]". implode('-',$model::getStopWords()),
            );
            $this->client->index(
                uid: $index,
            )->updateStopWords($model::getStopWords());

        } catch (ApiException $exception) {
            $this->warn(
                string: $exception->getMessage(),
            );

            return self::FAILURE;
        }

        return 0;
    }
}
