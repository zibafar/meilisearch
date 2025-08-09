<?php

namespace App\Http\Controllers;

use App\Traits\LogErrors;
use App\Traits\SendJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
 * @OA\Info(title="searcher-api", version="0.1")
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests,SendJsonResponse;


    /**
     * Log unhandled requests
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function notFound(Request $request): JsonResponse
    {
        return $this->sendError('Route not found.', [
            'error' => 'Not Found',
            'class' => __CLASS__,
            'line' => __LINE__,
        ], 404);
    }
}
