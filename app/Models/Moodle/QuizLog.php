<?php

namespace App\Models\Moodle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class QuizLog extends _BaseLMSModel
{
    use HasFactory,
        QLogRelations,
        QLogScopes,
        QLogAttributes;


    protected $table = 'quizaccess_proctoring_logs';

    public $timestamps = false;
    protected $fillable = [
        'proctoringrequired',
        'quizid'
    ];


}

trait QLogRelations
{
    /**
     * @return BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'courseid', 'id');
    }

    public function face():HasOne
    {
        return $this->hasOne(MdlFaceImage::class,'parentid','id')->where('parent_id','camshot_image');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'userid', 'id')->withoutGlobalScopes();
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'quizid', 'id');
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

trait QLogScopes
{


}

trait QLogAttributes
{

    public function getStatusAttribute()
    {
        $trans = 'access_quiz_plugin.status_webcam_log.';
        if (empty($this->webcampicture)) {
            $msg = 'not_taken';
            return [
                'msg' => $msg,
                'code' => 'error',
                'fa_msg' => __($trans . $msg)
            ];
        }
        if ($this->awsFlag == 0) {
            $msg = 'not-analyzed';
            return [
                'msg' => $msg,
                'code' => 'error',
                'fa_msg' => __($trans . $msg)
            ];
        }
        if ($this->awsFlag == 1) {
            $msg = 'analysing';
            return [
                'msg' => $msg,
                'code' => 'warning',
                'fa_msg' => __($trans . $msg)
            ];
        }
        if ($this->awsFlag == 3) {
            $msg = 'not_face';
            return [
                'msg' => $msg,
                'code' => 'error',
                'fa_msg' => __($trans . $msg)
            ];
        }
        if ($this->awsscore == 0) {
            $msg = 'not_match';
            return [
                'msg' => $msg,
                'code' => 'error',
                'fa_msg' => __($trans . $msg)
            ];
        }
        if ($this->awsscore == 100) {
            $msg = 'match';
            return [
                'msg' => $msg,
                'code' => 'success',
                'fa_msg' => __($trans . $msg)
            ];
        }
        if($this->face->facefound > 1){
            $msg = 'many_faces';
            return [
                'msg' => $msg,
                'code' => 'success',
                'fa_msg' => __($trans . $msg)
            ];
        }
        if($this->face->facefound == 0){
            $msg = 'not_face';
            return [
                'msg' => $msg,
                'code' => 'success',
                'fa_msg' => __($trans . $msg)
            ];
        }
    }
}
