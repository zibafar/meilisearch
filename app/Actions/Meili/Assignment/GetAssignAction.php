<?php

namespace App\Actions\Meili\Assignment;

use App\Actions\Meili\BaseMeiliAction;
use App\Models\Moodle\Meili\MeiliAssign;

class GetAssignAction extends BaseMeiliAction
{
    public static function getModel()
    {
        return MeiliAssign::class;
    }
}
