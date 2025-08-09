<?php


namespace App\Traits;


use Illuminate\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

trait LogErrors
{

    public function logError(Throwable $ex, &$msgReturn = '')
    {
        //todo
        $msg = $ex->getMessage();
        $msgReturn = null;

        if (strpos($msg, 'Duplicate')) {
            preg_match("/for key '(?<KEY>[^']+)'/", $msg, $output_array);
            $msgReturn = __('db-duplicate.' . $output_array['KEY']);
        }

        Log::channel('emed')->error('logError Throwable ex', ['msg' => $msg,]);
        Container::getInstance()->make(
            ExceptionHandler::class
        )->report($ex);
    }
}
