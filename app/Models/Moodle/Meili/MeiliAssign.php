<?php

namespace App\Models\Moodle\Meili;

use App\Contracts\ISearchAttributes;
use App\Models\Moodle\_BaseLMSModel;
use App\Scopes\Meili\AssignmentScope;
use App\Traits\Model\AssignGetSearchAttributes;
use Laravel\Scout\Searchable;

/**
 * Class MeiliAssign.
 *
 * @package namespace App\Models\Moodle\Meili;
 * @OA\Schema (schema="MeiliAssign")
 * **/
class MeiliAssign extends _BaseLMSModel implements ISearchAttributes
{
    use Searchable,AssignGetSearchAttributes{
        AssignGetSearchAttributes::toSearchableArray insteadof Searchable;
    }

    const INDEX = 'assign';

    protected $table = 'course';
    public $timestamps = false;
    protected $casts = [
        "modified" => 'datetime:Y-m-d H:i',
        "modified_sort" => 'datetime:Y-m-d H:i',
    ];
    protected $fillable = [
        'id',
        'modified',
        'modified_sort'
    ];
    protected $keyType = 'string';



    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new AssignmentScope());
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




