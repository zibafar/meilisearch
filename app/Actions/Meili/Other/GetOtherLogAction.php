<?php

namespace App\Actions\Meili\Other;

use App\Traits\LogErrors;

class GetOtherLogAction extends GetOtherAction
{
    use LogErrors;
    /**
     * Retrieve the list of Forums by the given filter.
     */
    public function run(...$args): array|bool
    {
        info("get other action call");
        try {
            return parent::run(...$args);
        } catch (\Throwable $ex) {
            $this->logError($ex);
            return false;
        }
    }
}
