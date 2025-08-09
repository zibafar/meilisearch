<?php

namespace App\Console\Commands;

use App\Console\Commands\Meilisearch\__Command;
use MeiliSearch\Client as MeiliSearchClient;
use Meilisearch\Exceptions\ApiException;

class FilterFields extends __Command
{
    protected $signature = 'scout:filters
        {index : The index you want to work with.}
    ';

    protected $description = 'Register filters against a search index.';

    public function handle(): int
    {
        $this->logInfo();

        $index = $this->argument(
            key: 'index',
        );

        $model = $this->getModelByIndex($index);

        try {
            $this->info(
                string: "Updating filterable attributes for [$model] on index [$index]",
            );
            $this->client->index(
                uid: $index,
            )->updateFilterableAttributes(
                filterableAttributes: $model::getSearchFilterAttributes(),
            );

            $this->info(
                string: "Updating searchable attributes for [$model] on index [$index]",
            );
            $this->client->index(
                uid: $index,
            )->updateSearchableAttributes(
                $model::getSearchHighlightAttributes(),
            );


        } catch (ApiException $exception) {
            $this->warn(
                string: $exception->getMessage(),
            );

            return self::FAILURE;
        }

        return 0;
    }
}
