<?php
namespace App\Contracts;

use App\Models\Moodle\_BaseLMSModel;

interface IMeiliConfig {
    /**
     * @param array|\Meilisearch\Search\SearchResult $query
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getPaginate(array|\Meilisearch\Search\SearchResult $query, int $limit, int $page): array;

    /**
     * @param array|\Meilisearch\Search\SearchResult $query
     * @return array
     */
    public function getData(array|\Meilisearch\Search\SearchResult $query): array;
    /**
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getConfig(int $limit, int $page, array $filters = [], string $sort = "modified_desc"): array;

}
