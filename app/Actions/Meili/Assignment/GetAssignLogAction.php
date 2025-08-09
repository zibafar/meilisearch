<?php

namespace App\Actions\Meili\Assignment;

use App\Traits\LogErrors;

class GetAssignLogAction extends GetAssignAction
{
    use LogErrors;
    /**
     * Retrieve the list of assignments by the given filter.
     */
    public function run(...$args): array|bool
    {
        info("get assignments from action call");
        try {
            return parent::run(...$args);
        } catch (\Throwable $ex) {
            $this->logError($ex);
            return false;
        }
    }
}
