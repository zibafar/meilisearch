<?php

namespace App\Actions\Meili\Forum;

use App\Traits\LogErrors;

class GetForumLogAction extends GetForumAction
{
    use LogErrors;
    /**
     * Retrieve the list of Forums by the given filter.
     */
    public function run(...$args): array|bool
    {
        info("get Forum action call");
        try {
            return parent::run(...$args);
        } catch (\Throwable $ex) {
            $this->logError($ex);
            return false;
        }
    }
}
