<?php

namespace App\Http\Resources\Post;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'subject' => $this->subject,
            'message' => $this->message,
            'discussion' => @$this->mdldiscussion->name,

            'forum' => [
                'name' => @$this->mdldiscussion->mdlforum->name,
                'intro' => @$this->mdldiscussion->mdlforum->intro,
                'course_name' => @$this->mdldiscussion->mdlforum->mdlcourse->fullname
            ],

            'modified'=>$this->modified
        ];
    }

}
