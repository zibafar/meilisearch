<?php

namespace App\Actions\Meili\Quiz;

use App\Actions\Meili\BaseMeiliAction;
use App\Models\Moodle\Meili\MeiliAssign;
use App\Models\Moodle\Meili\MeiliQuiz;

class GetQuizAction extends BaseMeiliAction
{

    public static function getModel()
    {//todo: quiz security
        return MeiliQuiz::class;
    }

}
