<?php

namespace App\Http\Resources\Meili;


use App\Models\Moodle\_BaseLMSModel;
use App\Traits\Resource\Details;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AssignmentResource extends JsonResource
{
    use Details;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        //0=>coures , 1=>assign , 2=>cm_id
        $ids = explode('-', $this->id);
        return [
            'id' => $this->id,
            'assign_name'=>$this->detail($ids,$this->assign_name,'assign'),
            'assign_intro'=>$this->detail($ids,$this->assign_intro,'assign'),
            'course_fullname'=>$this->detail($ids,$this->course_fullname,'course'),
            'modified' => $this->modified_sort
        ];
    }



}
