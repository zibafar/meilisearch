<?php

namespace App\Http\Resources\Meili;


use App\Models\Moodle\_BaseLMSModel;
use App\Traits\Resource\Details;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CourseResource extends JsonResource
{
    use Details;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        //0=>coures , 1=>category
        $ids = explode('-', $this->id);
        return [
            'id' => $this->id,

            'category_name'=>$this->detail($ids,$this->category_name,'category'),
            'category_description'=>$this->detail($ids,$this->category_description,'category'),

            'course_shortname'=>$this->detail($ids,$this->course_shortname,'course'),
            'course_fullname'=>$this->detail($ids,$this->course_fullname,'course'),
            'course_summary'=>$this->detail($ids,$this->course_summary,'course'),

            'modified' => $this->modified_sort
        ];
    }



}
