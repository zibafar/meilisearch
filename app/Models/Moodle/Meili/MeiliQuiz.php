<?php

namespace App\Models\Moodle\Meili;

use App\Contracts\ISearchAttributes;
use App\Models\Moodle\_BaseLMSModel;
use App\Scopes\Meili\QuizScope;
use App\Traits\Model\QuizGetSearchAttributes;
use Laravel\Scout\Searchable;
/**
 * Class MeiliQuiz.
 *
 * @package namespace App\Models\Moodle\Meili;
 * @OA\Schema (schema="MeiliQuiz")
 * **/
class MeiliQuiz extends _BaseLMSModel implements ISearchAttributes
{
    use Searchable , QuizGetSearchAttributes{
        QuizGetSearchAttributes::toSearchableArray insteadof Searchable;
    }

    const INDEX = 'quiz';
    protected $fillable = [
        "id" ,
        "course_id" ,
        "course_fullname" ,
        "quiz_id" ,
        "quiz_name" ,
        "quiz_intro" ,
        "timeopen",
        "timeclose",
        "question_id" ,
        "question_name" ,
        "question_text" ,
        "answer"  ,
        "modified"  ,
        "modified_sort"
    ];

    protected $table = 'course';
    public $timestamps = false;
    protected $casts = [
        "modified" => 'datetime:Y-m-d H:i',
        "modified_sort" => 'datetime:Y-m-d H:i',
        "timeclose" => 'datetime:Y-m-d H:i',
        "timeopen" => 'datetime:Y-m-d H:i',
    ];
    protected $keyType = 'string';



    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new QuizScope());
    }
    #-----------Meilisearch ----------------------

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return self::INDEX;
    }

}




