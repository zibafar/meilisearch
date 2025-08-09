<?php

namespace App\Http\Resources\FaceLogs;

use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaceLogResource extends JsonResource
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
            "student_id" => $this->userid,
            "student_name" => @$this->student->full_name,
            "picture" => $this->webcampicture,

        ];
    }

}
