<?php

namespace App\Actions\Post;

use App\Traits\LogErrors;

class GetPostLogAction extends GetPostAction
{
    use LogErrors;
    /**
     * Retrieve the list of users by the given filter.
     */
    public function run(...$args): \Illuminate\Contracts\Pagination\LengthAwarePaginator|bool
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
