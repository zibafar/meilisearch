<?php

namespace App\Http\Resources\Meili;


use App\Traits\Resource\Details;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ForumResource extends JsonResource
{
    use Details;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        //0=>coures , 1=>forum , 2=>discusson 3=>post
        $ids = explode('-', $this->id);
        return [
            'id' => $this->id,

            'course_fullname' => $this->detail($ids, $this->course_fullname, type: 'course'),

            'subject' => $this->detail($ids, $this->subject, type: 'post'),
            'message' => $this->detail($ids, $this->message, type: 'post'),

            'discussion_name' => $this->detail($ids, $this->discussion_name, type: 'discuss'),

            'forum_name' => $this->detail($ids, $this->forum_name, type: 'forum'),
            'forum_intro' => $this->detail($ids, $this->forum_intro, type: 'forum'),

            'modified_sort' => $this->modified_sort
        ];
    }


}
