<?php

namespace App\Http\Resources\Meili;


use App\Models\Moodle\_BaseLMSModel;
use App\Traits\Resource\Details;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class QuizResource extends JsonResource
{
    use Details;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        //0=>coures , 1=>quiz 2=>slot 3=>question 4=>answer
        $ids = explode('-', $this->id);
        return [
            'id' => $this->id,
            'answer'=>$this->detail($ids,$this->answer,'answer'),

            'quiz_name'=>$this->detail($ids,$this->quiz_name,'quiz'),
            'quiz_intro'=>$this->detail($ids,$this->quiz_intro,'quiz'),

            'question_name'=>$this->detail($ids,$this->question_name,'question'),
            'question_text'=>$this->detail($ids,$this->question_text,'question'),

            'course_fullname'=>$this->detail($ids,$this->course_fullname,'course'),

            'modified' => $this->modified_sort
        ];
    }



}
