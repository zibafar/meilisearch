<?php

namespace App\Traits;

use App\Models\Moodle\_BaseLMSModel;
use Illuminate\Support\Str;
use Meilisearch\Search\SearchResult;

trait MeiliConfig
{


    /**
     * @param array|SearchResult $query
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getPaginate(array|SearchResult $query, int $limit, int $page): array
    {
        return [
            "total" => $query->getTotalHits(),
            "count" => $query->getHitsCount(),
            "per_page" => $limit,
            "current_page" => $page,
            "total_pages" => $total_pages = $query->getTotalPages(),
            "next_page" => $page == $total_pages ? '-' : $page + 1,
            "previous_page" => ($page - 1) == 0 ? '-' : ($page - 1)
        ];
    }

    /**
     * @param array|SearchResult $query
     * @return array
     */
    public function getData(array|SearchResult $query): array
    {
        $formated = array_column($query->getHits(), '_formatted');
        return array_map(fn($v)=>(object)$v, $formated);
    }

    /**
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getConfig(int $limit, int $page, array $filters = [], string $sort = "modified_desc"): array
    {
        $filter = static::getModel()::prepareFilters($filters);
        $sort = static::getModel()::prepareSort($sort);
        return [
            'attributesToRetrieve' =>  static::getModel()::getSearchRetrieveAttributes(),
            'hitsPerPage' => $limit,
            'limit' => $limit,
            'page' => $page,
            'filter' => $filter,
            'sort' => $sort,
            'attributesToHighlight' => static::getModel()::getSearchHighlightAttributes(),
            'highlightPreTag' => _BaseLMSModel::MARKER_OPEN,
            'highlightPostTag' => _BaseLMSModel::MARKER_CLOSE,
            'attributesToCrop' => static::getModel()::getSearchCropAttributes(),
            'cropLength' => 7
        ];
    }


}
