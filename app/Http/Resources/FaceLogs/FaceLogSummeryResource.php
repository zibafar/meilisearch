<?php

namespace App\Http\Resources\FaceLogs;

use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaceLogSummeryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            "picture" => $this->webcampicture,
            "status" => $this->status,

        ];
    }

}
