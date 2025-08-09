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

class Discussion extends _BaseLMSModel
{
    use Searchable,
        HasFactory,
        ForumDiscussionRelations,
        ForumDiscussionAttributes;


    protected $table = 'forum_discussions';

    public $timestamps = false;
    protected $fillable = [
        'id'

    ];
    protected $casts = [
        "timemodified" => 'datetime:Y-m-d H:i',
    ];
//    protected $with = [
//        'mdlforum'
//    ];

    #-----------Meilisearch ----------------------




}

trait ForumDiscussionRelations
{
    public function mdlcourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course', 'id')->withoutGlobalScopes();
    }

    public function mdlforum(): BelongsTo
    {
        return $this->belongsTo(Forum::class, 'forum', 'id')->withoutGlobalScopes();
    }



}

trait ForumDiscussionAttributes
{

    /**
     * Get the intro search
     */

}


