<?php

namespace App\Models\Moodle\Meili;

use App\Contracts\ISearchAttributes;
use App\Models\Moodle\_BaseLMSModel;
use App\Scopes\Meili\VideoScope;
use App\Traits\Model\VideoGetSearchAttributes;
use Laravel\Scout\Searchable;

/**
 * Class MeiliVideo.
 *
 * @package namespace App\Models\Moodle\Meili;
 * @OA\Schema (schema="MeiliVideo")
 * **/
class MeiliVideo extends _BaseLMSModel implements ISearchAttributes
{
    use Searchable,VideoGetSearchAttributes{
        VideoGetSearchAttributes::toSearchableArray insteadof Searchable;
    }



    const INDEX = 'video';

    protected $table = 'course';
    public $timestamps = false;
    protected $casts = [
        "modified" => 'datetime:Y-m-d H:i',
        "modified_sort" => 'datetime:Y-m-d H:i',
    ];
    protected $fillable = [
        'id',
        'course_id',
        'course_fullname',
        'video_id',
        'video_name',
        'video_stream',
        'bookmark_id',
        'bookmark_text',
        'bookmark_time',
        'user_id',
        'modified',
        'modified_sort'
    ];
    protected $keyType = 'string';



    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new VideoScope());
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




