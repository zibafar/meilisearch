<?php

namespace App\Actions\Meili\Video;

use App\Actions\Meili\BaseMeiliAction;
use App\Models\Moodle\Meili\MeiliVideo;

class GetVideoAction  extends BaseMeiliAction
{

    public static function getModel()
    {
        return MeiliVideo::class;
    }

}
