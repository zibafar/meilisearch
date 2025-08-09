<?php

namespace App\Models\Moodle\Meili;

use App\Contracts\ISearchAttributes;
use App\Models\Moodle\_BaseLMSModel;
use App\Scopes\Meili\ForumScope;
use App\Traits\Model\ForumGetSearchAttributes;
use Laravel\Scout\Searchable;

class MeiliForum extends _BaseLMSModel implements ISearchAttributes
{
    use Searchable , ForumGetSearchAttributes{
        ForumGetSearchAttributes::toSearchableArray insteadof Searchable;
    }

    const INDEX = 'forum';
    protected $fillable = [
        'id',
        'course_id',
        'course_fullname',
        'forum_id',
        'forum_name',
        'forum_intro',
        'discussion_id',
        'discussion_name',
        'post_id',
        'user_id',
        'parent',
        'subject',
        'message',
        'deleted',
        'modified', //coalese(c.modfU f.modf, d.modfU p.mof)
        'created',
        'wordcount',
        'charcount'
    ];

    protected $table = 'course';
    public $timestamps = false;
    protected $casts = [
        "modified" => 'datetime:Y-m-d H:i',
        "modified_sort" => 'datetime:Y-m-d H:i',
    ];
    protected $keyType = 'string';



    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ForumScope());
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




