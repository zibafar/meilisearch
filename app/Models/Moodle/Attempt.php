<?php

namespace App\Models\Moodle;
use App\Scopes\MdlContextScope;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Attempt extends _BaseLMSModel
{
    use HasFactory;
    protected $table = 'quiz_attempts';

    public $timestamps=false;
    protected $fillable = [
        'quiz', 'userid', 'attempt', 'uniqueid', 'layout', 'currentpage', 'preview', 'state', 'timestart', 'timefinish', 'timemodified'
    ];

    protected $visible=[];



}
