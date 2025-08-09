<?php

namespace App\Models\Moodle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MdlFaceImage extends _BaseLMSModel
{
    use HasFactory,
        FaceImageRelations,
        FaceImageScopes,
        FaceImageAttributes;


    protected $table = 'proctoring_face_images';

    public $timestamps = false;
    protected $fillable = [
        'parent_type',
        'parentid',
        'faceimage',
        'facefound'
    ];


}

trait FaceImageRelations
{
    public function file()
    {
        return $this->hasOne(MdlFile::class,  'itemid','parentid')
            ->where('component','quizaccess_proctoring')
            ->latestOfMany()
            ->withDefault([
                'filename' => '404',
            ]);

    }

}

trait FaceImageScopes
{


}

trait FaceImageAttributes
{
}
