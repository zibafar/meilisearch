<?php

namespace App\Actions\Meili\Quiz;

use App\Traits\LogErrors;

class GetQuizLogAction extends GetQuizAction
{
    use LogErrors;
    /**
     * Retrieve the list of Quiz by the given filter.
     */
    public function run(...$args): array|bool
    {
        info("get Quiz action call");
        try {
            return parent::run(...$args);
        } catch (\Throwable $ex) {
            $this->logError($ex);
            return false;
        }
    }
}
