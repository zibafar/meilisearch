<?php

namespace App\Actions\Meili\Course;

use App\Actions\Meili\BaseMeiliAction;
use App\Models\Moodle\Meili\MeiliCourse;

class GetCourseAction extends BaseMeiliAction
{

    public static function getModel()
    {
        return MeiliCourse::class;
    }

}
