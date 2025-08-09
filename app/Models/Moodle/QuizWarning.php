<?php

namespace App\Models\Moodle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class QuizWarning extends _BaseLMSModel
{
    use HasFactory,
        QWRelations,
        QWScopes,
        QWAttributes;


    protected $table = 'proctoring_fm_warnings';

    public $timestamps=false;
    protected $fillable = [
        'reportid', //log.id
        'quizid',
        'courseid',
        'userid'
    ];





}

trait QWRelations
    {
    /**
     * @return BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class,'courseid','id');
    }
    public function log(): BelongsTo
    {
        return $this->belongsTo(QuizLog::class,'reportid','id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class,'userid','id')->withoutGlobalScopes();
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class,'quizid','id');
    }

    public function quiz(): HasOneThrough
    {
        return $this->hasOneThrough(
            Quiz::class,
            CourseModule::class,
            'id', // Foreign key on the course_modules table...
            'id', //Foreign key on the mdl_quiz table...
            'quizid',//Local key on the log table...
            'instance' //// Local key on the course_modules table...
        );
    }

    /**
     * Get the quiz .
     */



    }
trait QWScopes
    {


    }
trait QWAttributes
    {
    }
