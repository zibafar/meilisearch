<?php

namespace App\Models\Moodle;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class MdlRole extends _BaseLMSModel
{
    use HasFactory,
        MyRelations,
        MyScopes,
        MyAttributes;


    protected $table = 'role';

    public $timestamps=false;
    protected $fillable = [
        'id',
        'name',
        'shortname',
        'description',
        'sortorder',
        'archetype'
    ];



}

trait MyRelations
    {

    }
trait MyScopes
    {

    }
trait MyAttributes
    {
    public function getSeRoleAttribute(): string
    {
        return mappingRole($this->id);
    }
    }
