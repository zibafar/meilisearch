<?php

namespace App\Models\Moodle\Forum;

use App\Models\Moodle\_BaseLMSModel;
use App\Models\Moodle\Course;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

class Forum extends _BaseLMSModel
{
    use Searchable,
        HasFactory,
        ForumRelations,
        ForumAttributes;


    protected $table = 'forum';

    public $timestamps = false;
    protected $fillable = [
        'id',
        'course',
        'type',
        'name',
        'intro',
        'timemodified'
    ];
    protected $casts = [
        "timemodified" => 'datetime:Y-m-d H:i',
    ];
    protected $with = [
        'mdlcourse',
        'discussions'
    ];

    #-----------Meilisearch ----------------------


    public function searchable(): bool
    {
        return true;
    }
    /**
     * Get the index able data array for the model.
     *
     * @return array<string, mixed>
     */
    #[SearchUsingPrefix(['course'])]
    #[SearchUsingFullText(['name', 'intro_search'])]
    public function toSearchableArray(): array
    {
        $array= [
            'course' => (int)$this->course,
            'intro_search' => $this->intro_search,
            'course_name' => @$this->mdlcourse->fullname,
            'forum_discussions' =>implode(" | ",$this->discussions->pluck('name')->toArray()),
            'name' => $this->name
        ];

        return array_map('to_standard_letter',$array);
    }

    public static function getSearchFilterAttributes(): array
    {
        return [
            'course'
        ];
    }

    public static function getSearchSortAttributes(): array
    {
        return [
            'name',
            'timemodified'
        ];
    }



}

trait ForumRelations
{
    public function mdlcourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course', 'id')->withoutGlobalScopes();
    }

    public function discussions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Discussion::class,'forum','id') ;
    }


}

trait ForumAttributes
{

    /**
     * Get the intro search
     */
    protected function introSearch(): Attribute
    {
        $intro = strip_tags($this->intro);
        return Attribute::make(
            get: fn() => $intro
        );
    }
}


