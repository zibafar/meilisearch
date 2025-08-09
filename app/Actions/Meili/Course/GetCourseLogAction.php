<?php

namespace App\Actions\Meili\Course;

use App\Traits\LogErrors;

class GetCourseLogAction extends GetCourseAction
{
    use LogErrors;
    /**
     * Retrieve the list of Course by the given filter.
     */
    public function run(...$args): array|bool
    {
        info("get Course action call");
        try {
            return parent::run(...$args);
        } catch (\Throwable $ex) {
            $this->logError($ex);
            return false;
        }
    }
}
