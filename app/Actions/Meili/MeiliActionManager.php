<?php

namespace App\Actions\Meili;

use App\Actions\Meili\Assignment\GetAssignAction;
use App\Actions\Meili\Forum\GetForumAction;
use App\Actions\Meili\Quiz\GetQuizAction;
use App\Actions\Meili\Video\GetVideoAction;
use App\Models\Moodle\Meili\MeiliForum;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Manager;
use Meilisearch\Client as MeiliSearchClient;

class MeiliActionManager extends Manager
{

    public function getDefaultDriver()
    {
        return MeiliForum::INDEX;
    }


//    public function createForumDriver(): GetForumAction
//    {
//        return app(GetForumAction::class);
//    }
//    public function createVideoDriver()
//    {
//        return app(GetVideoAction::class);
//    }

//    public function createAssignDriver()
//    {
//        return app(GetAssignAction::class);
//    }

//    public function createQuizDriver()
//    {
//        return app(GetQuizAction::class);
//    }

}
