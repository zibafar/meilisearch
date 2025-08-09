<?php

namespace App\Actions\Meili\Forum;

use App\Actions\Meili\BaseMeiliAction;
use App\Models\Moodle\Meili\MeiliForum;
use App\Models\Moodle\Meili\MeiliOther;
use App\Models\Moodle\Meili\MeiliQuiz;

class GetForumAction extends BaseMeiliAction
{

    public static function getModel()
    {
        return MeiliForum::class;
    }


}
