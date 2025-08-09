<?php

namespace App\Http\Resources\FaceLogs;

use App\Http\Resources\PaginationResource;
use App\Http\Resources\Quiz\QuizResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaceLogPaginateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        $pagination = new PaginationResource($this);

        return [
            'webcam_reports' =>FaceLogResource::collection($this),
            $pagination::$wrap => $pagination,
        ];
    }
}
