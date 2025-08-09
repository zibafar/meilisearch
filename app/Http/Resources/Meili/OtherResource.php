<?php

namespace App\Http\Resources\Meili;


use App\Traits\Resource\Details;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class OtherResource extends JsonResource
{
    use Details;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        //0=>coures , 2=>url 3=>page
        $ids = explode('-', $this->id);
        return [
            'id' => $this->id,

            'course_fullname' => $this->detail($ids, $this->course_fullname, type: 'course'),

            'url_name' => $this->detail($ids, $this->url_name, type: 'url'),
            'url_intro' => $this->detail($ids, $this->url_intro, type: 'url'),
            'url_external' => $this->detail($ids, $this->externalurl, type: 'url', link: Str::unmark($this->externalurl)),

            'page_name' => $this->detail($ids, $this->page_name, type: 'page'),
            'page_intro' => $this->detail($ids, $this->page_intro, type: 'page'),
            'page_content' => $this->detail($ids, $this->page_content, type: 'page'),

            'modified_sort' => $this->modified_sort


        ];
    }


}
