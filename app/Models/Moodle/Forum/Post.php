<?php

namespace App\Models\Moodle\Forum;

use App\Models\Moodle\_BaseLMSModel;
use App\Models\Moodle\Course;
use App\Models\Moodle\User;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

class Post extends _BaseLMSModel
{
    use Searchable,
        HasFactory,
        PostRelations,
        PostAttributes;


    protected $table = 'forum_posts';

    public $timestamps = false;
    protected $fillable = [
        'id',
        'discussion',
        'userid',
        'parent',
        'subject',
        'message',
        'deleted',
        'modified',
        'created',
        'wordcount',
        'charcount'
    ];
    protected $casts = [
        "modified" => 'datetime:Y-m-d H:i',
        "created" => 'datetime:Y-m-d H:i',
    ];
    protected $with = [
        'mdldiscussion'
    ];

    #-----------Meilisearch ----------------------
    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'post';
    }

    public function scopePublished($query)
    {
        return $query->where('deleted', 0)->where('wordcount' ,'>' ,0);
    }
    /**
     * Get the index able data array for the model.
     *
     * @return array<string, mixed>
     */
    #[SearchUsingFullText(['subject', 'message','discussion'])]
    public function toSearchableArray(): array
    {
        $array = [
            'subject' => $this->subject,
            'message' => $this->message_search,
            'discussion' => @$this->mdldiscussion->name
        ];
        $array =array_map('to_standard_letter', $array);
        $forum = [
            'name' => @$this->mdldiscussion->mdlforum->name,
            'intro' => @$this->mdldiscussion->mdlforum->intro_search,
            'course_name' => @$this->mdldiscussion->mdlforum->mdlcourse->fullname
        ];
        $forum= array_map('to_standard_letter', $forum);
        return $array + ['forum'=> $forum];
    }

    public static function getSearchFilterAttributes(): array
    {
        return [
            'discussion',
            'id'
        ];
    }

    public static function getSearchSortAttributes(): array
    {
        return [
            'modified'
        ];
    }


}

trait PostRelations
{
    public function mdldiscussion(): BelongsTo
    {
        return $this->belongsTo(Discussion::class, 'discussion', 'id')->withoutGlobalScopes();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id')->withoutGlobalScopes();
    }


}

trait PostAttributes
{

    /**
     * Get the intro search
     */
    protected function messageSearch(): Attribute
    {
        $msg = strip_tags($this->message);
        return Attribute::make(
            get: fn() => $msg
        );
    }
}


