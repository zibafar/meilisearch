<?php

namespace App\Models\Moodle\Meili;

use App\Contracts\ISearchAttributes;
use App\Models\Moodle\_BaseLMSModel;
use App\Scopes\Meili\CourseScope;
use App\Traits\Model\CourseGetSearchAttributes;
use Laravel\Scout\Searchable;
/**
 * Class MeiliCourse.
 *
 * @package namespace App\Models\Moodle\Meili;
 * @OA\Schema (schema="MeiliCourse")
 * **/
class MeiliCourse extends _BaseLMSModel implements ISearchAttributes
{
    use Searchable , CourseGetSearchAttributes{
        CourseGetSearchAttributes::toSearchableArray insteadof Searchable;
    }

    const INDEX = 'course';
    protected $fillable = [
        "id" ,
        "course_id" ,
        "course_fullname" ,
        "course_intro" ,
        "timeopen",
        "timeclose",
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

        static::addGlobalScope(new CourseScope());
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




