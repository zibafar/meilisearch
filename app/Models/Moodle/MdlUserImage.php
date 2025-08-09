<?php

namespace App\Models\Moodle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MdlUserImage extends _BaseLMSModel
{
    use HasFactory,
        UserImageRelations,
        UserImageScopes,
        UserImageAttributes;


    protected $table = 'proctoring_user_images';

    public $timestamps = false;
    protected $fillable = [
        'id',
        'photo_draft_id',
        'userid'
    ];


}

trait UserImageRelations
{
    public function file()
    {
        return $this->hasOne(MdlFile::class,  'itemid','photo_draft_id')
            ->where('component','user')
            ->where('filearea','draft')
            ->latestOfMany()
            ->withDefault([
                'filename' => '404',
            ]);
;
    }

}

trait UserImageScopes
{


}

trait UserImageAttributes
{
}
