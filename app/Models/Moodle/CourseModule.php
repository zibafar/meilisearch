<?php

namespace App\Models\Moodle;
use App\Scopes\QuizModuleScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CourseModule extends _BaseLMSModel
{
    use HasFactory,
        CourseModuleRelations;


    protected $table = 'course_modules';

    public $timestamps=false;
    protected $fillable = [
        'course',
        'module',
        'instanceid',
        'idnumber'
    ];

    protected $visible=[];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new QuizModuleScope());
    }


}

trait CourseModuleRelations
{
    /**
     * @return BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class,'instance','id');
    }

    public function files(): HasManyThrough
    {
        return $this->hasManyThrough(
            MdlFile::class,
            MdlContext::class,
            'instanceid',
            'contextid',
            'id',
            'id'
        );
    }


}

