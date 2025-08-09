<?php

namespace App\Models\Moodle;
use App\Scopes\MdlContextScope;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MdlContext extends _BaseLMSModel
{
    use HasFactory;
    protected $table = 'context';

    public $timestamps=false;
    protected $fillable = [
        'contextlevel',
        'path',
        'instanceid'
    ];

    protected $visible=[];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new MdlContextScope());
    }

    /**
     * @return BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class,'instance','id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class,'instance','id');
    }


}
