<?php

namespace App\Models\Moodle\Meili;

use App\Contracts\ISearchAttributes;
use App\Models\Moodle\_BaseLMSModel;
use App\Scopes\Meili\OtherScope;
use App\Traits\Model\OtherGetSearchAttributes;
use Laravel\Scout\Searchable;

class MeiliOther extends _BaseLMSModel implements ISearchAttributes
{
    use Searchable , OtherGetSearchAttributes{
        OtherGetSearchAttributes::toSearchableArray insteadof Searchable;
    }

    const INDEX = 'other';
    protected $fillable = [
        'id',
        'course_id',
        'course_fullname',
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

        static::addGlobalScope(new OtherScope());
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




