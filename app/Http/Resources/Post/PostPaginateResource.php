<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostPaginateResource extends JsonResource
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
            'posts' =>PostResource::collection($this),
            $pagination::$wrap => $pagination,
        ];
    }
}
