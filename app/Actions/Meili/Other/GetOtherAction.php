<?php

namespace App\Actions\Meili\Other;

use App\Actions\Meili\BaseMeiliAction;
use App\Models\Moodle\Meili\MeiliForum;
use App\Models\Moodle\Meili\MeiliOther;
use App\Models\Moodle\Meili\MeiliQuiz;

class GetOtherAction extends BaseMeiliAction
{

    public static function getModel(): string
    {
       return MeiliOther::class;
    }
}
