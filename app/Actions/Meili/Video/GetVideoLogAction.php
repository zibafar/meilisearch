<?php

namespace App\Actions\Meili\Video;

use App\Traits\LogErrors;

class GetVideoLogAction extends GetVideoAction
{
    use LogErrors;
    /**
     * Retrieve the list of users by the given filter.
     */
    public function run(...$args): array|bool
    {
        info("get posts action call");
        try {
            return parent::run(...$args);
        } catch (\Throwable $ex) {
            $this->logError($ex);
            return false;
        }
    }
}
