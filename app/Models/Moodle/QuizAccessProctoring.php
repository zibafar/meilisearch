<?php

namespace App\Models\Moodle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAccessProctoring extends _BaseLMSModel
{
    use HasFactory,
        QuizAccessProctoringRelations,
        QuizAccessProctoringScopes,
        QuizAccessProctoringAttributes;


    protected $table = 'quizaccess_proctoring';

    public $timestamps=false;
    protected $fillable = [
        'proctoringrequired',
        'quizid'
    ];



}

trait QuizAccessProctoringRelations
    {
    /**
     * @return BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class,'quizid','id');
    }


    }
trait QuizAccessProctoringScopes
    {


    }
trait QuizAccessProctoringAttributes
    {
    }
