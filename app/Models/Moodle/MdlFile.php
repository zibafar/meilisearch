<?php

namespace App\Models\Moodle;

use App\Scopes\MdlFileScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class MdlFile extends _BaseLMSModel
{
    use HasFactory,
        FRelations;


    protected $table = 'files';

    public $timestamps = false;
    protected $fillable = [
        'contenthash',
        'pathname',
        'contextid',
        'component',
        'filearea',
        'itemid',
        'filepath',
        'filename',
        'userid',
        'filesize',
        'mimetype',
        'status',
        'source',
        'author',
        'license',
        'timecreated',
        'timemodified',
        'sortorder',
        'referencefileid'
    ];
    protected $casts = [
        "timecreated" => 'datetime:Y-m-d H:i',
        "timemodified" => 'datetime:Y-m-d H:i',
    ];

    /*
     *  @return void
    */
    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new MdlFileScope());
    }

}

trait FRelations
{
    public function module(): HasOneThrough
    {
        return $this->hasOneThrough(
            CourseModule::class,
            MdlContext::class,
            'id',
            'id',
            'contextid',
            'instanceid'
        );
    }

    public function quiz(){
        return $this->module->quiz();
    }

    public function user(){
        return $this->belongsTo(User::class,'userid','id')->withoutGlobalScopes();
    }

}


