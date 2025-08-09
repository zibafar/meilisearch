<?php

namespace App\Actions\Meili;

use App\Contracts\IMeiliConfig;
use App\Models\Moodle\Meili\MeiliForum;
use App\Traits\MeiliConfig;
use Meilisearch\Client as MeiliSearchClient;
use function PHPUnit\Framework\throwException;

abstract class BaseMeiliAction implements IMeiliConfig
{
    use MeiliConfig;

    private MeiliSearchClient $client;

    public function __construct()
    {
       $this->client = app(MeiliSearchClient::class);
    }

    abstract public static function getModel();
    /**
     * @param string $search
     * @param array $filters
     * @param string $sort
     * @param int $limit
     * @return array[]|bool
     */
    public function run(string $search, array $filters, string $sort, int $limit = 50, int $page = 1) : array|bool
    {
        $query = $this->client
            ->index($this->getModel()::INDEX)
            ->search(
                $search,
                $this->getConfig($limit, $page, $filters, $sort)
            );
        return [
            "data" => $this->getData($query),
            "paginate" => $this->getPaginate($query, $limit, $page)
        ];
    }

}
