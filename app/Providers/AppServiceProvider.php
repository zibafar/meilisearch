<?php

namespace App\Providers;

use App\Actions\Meili\Assignment\GetAssignAction;
use App\Actions\Meili\Assignment\GetAssignLogAction;
use App\Actions\Meili\Course\GetCourseAction;
use App\Actions\Meili\Course\GetCourseLogAction;
use App\Actions\Meili\Forum\GetForumAction;
use App\Actions\Meili\Forum\GetForumLogAction;
use App\Actions\Meili\MeiliActionManager;
use App\Actions\Meili\Other\GetOtherAction;
use App\Actions\Meili\Other\GetOtherLogAction;
use App\Actions\Meili\Quiz\GetQuizAction;
use App\Actions\Meili\Quiz\GetQuizLogAction;
use App\Actions\Meili\Video\GetVideoAction;
use App\Actions\Meili\Video\GetVideoLogAction;
use App\Macros\StrMixin;
use App\Models\Moodle\Meili\MeiliAssign;
use App\Models\Moodle\Meili\MeiliForum;
use App\Models\Moodle\Meili\MeiliQuiz;
use App\Models\Moodle\Meili\MeiliVideo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Meilisearch\Client as MeiliSearchClient;
use function Clue\StreamFilter\fun;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->manageMeiliAction();

    }

    /**
     * Bootstrap any application services.
     * @throws \ReflectionException
     */
    public function boot(): void
    {
        URL::forceScheme(env('SCHEMA', 'http'));

        $this->app->singleton(MeiliSearchClient::class, function ($app) {
            return new MeiliSearchClient(config('scout.meilisearch.host'),
                config('scout.meilisearch.key'));
        });

        Str::mixin(new StrMixin());

        $dbg = !empty($_COOKIE['XDEBUG_SESSION'] ?? ''); // false; //DEBUG      $dbg = true;
        if ($dbg) {
            DB::listen(function ($query) {
                Log::info("Query: " . $query->sql);
                Log::info("Bindings: " . implode(', ', $query->bindings));
                Log::info("Time: " . $query->time);
            });
        }
    }

    /**
     * @return void
     */
    public function manageMeiliAction(): void
    {
        $this->app->singleton(MeiliActionManager::class);

        $actionClasses = [
            GetCourseAction::class => GetCourseLogAction::class,
            GetQuizAction::class => GetQuizLogAction::class,
            GetForumAction::class => GetForumLogAction::class,
            GetAssignAction::class => GetAssignLogAction::class,
            GetVideoAction::class => GetVideoLogAction::class,
            GetOtherAction::class => GetOtherLogAction::class
        ];

        foreach ($actionClasses as $action => $log) {
            app(MeiliActionManager::class)->extend($action::getModel()::INDEX, function () use ($action) {
                return app($action);
            });
            if (config('action.log', true)) {
                $this->app->bind($action, $log);
            }
        }


    }
}
