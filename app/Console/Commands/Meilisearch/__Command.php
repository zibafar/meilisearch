<?php

namespace App\Console\Commands\Meilisearch;

use App\Models\Moodle\Meili\MeiliAssign;
use App\Models\Moodle\Meili\MeiliCourse;
use App\Models\Moodle\Meili\MeiliForum;
use App\Models\Moodle\Meili\MeiliOther;
use App\Models\Moodle\Meili\MeiliQuiz;
use App\Models\Moodle\Meili\MeiliVideo;
use Exception;
use Illuminate\Console\Command;
use Meilisearch\Client as MeiliSearchClient;
use Meilisearch\Exceptions\ApiException;

class __Command extends Command
{
    public mixed $client;
    protected array $models=[
        MeiliCourse::INDEX => MeiliCourse::class,
        MeiliForum::INDEX => MeiliForum::class,
        MeiliVideo::INDEX => MeiliVideo::class,
        MeiliAssign::INDEX => MeiliAssign::class,
        MeiliQuiz::INDEX => MeiliQuiz::class,
        MeiliOther::INDEX => MeiliOther::class,

    ];
    public function __construct()
    {
        $this->client=resolve(MeiliSearchClient::class);
        parent::__construct();
    }


    /**
     * @param string $index
     * @return string
     */
    public function getModelByIndex(string $index): string
    {
       return $this->models[$index] ?? '';
    }

    /**
     * @return array
     */
    public function getModels(): array
    {
        return   array_values($this->models);
    }
    public function getIndexes(): array
    {
        return   array_keys($this->models);
    }

    /**
     * @param string|null $index
     * @return string[]
     */
    public function ConvertToArr(string|null $index): array
    {
        $indexes = $index ?? $this->getIndexes();
        return is_array($indexes) ? $indexes : [$indexes];
    }

    public function getModifiedFieldByIndex(string $index): string
    {
        return 'modified';
//        return match ($index) {
//            MeiliForum::INDEX, MeiliVideo::INDEX => 'modified',
//            default => 'modified',
//        };
    }

    /**
     * @param ApiException|Exception $exception
     * @return void
     */
    public function report_error(ApiException|Exception $exception): void
    {
        error_log($exception->getMessage());
        $this->warn(
            string: $exception->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function logInfo(): void
    {
        info('command: ' . $this->signature .
            'description: ' . $this->description .
            ' class: ' . __CLASS__ . ',line' . __LINE__);
    }
}
