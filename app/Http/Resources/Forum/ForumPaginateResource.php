<?php

namespace App\Http\Resources\Forum;

use App\Http\Resources\Meili\ForumResource;
use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForumPaginateResource extends JsonResource
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
            'data' =>ForumResource::collection($this),
            $pagination::$wrap => $pagination,
        ];
    }
}
