<?php

namespace App\Models\Moodle;
use App\Builders\UserBuilder;
use App\Traits\DefaultOrderBy;
use App\Traits\TableInfo;
use App\Traits\User\UserRole;
use App\Traits\UsesUuid;
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
use LaracraftTech\LaravelDateScopes\DateScopes;

class Student extends User
{
    use HasFactory,
        StudentRelations,
        StudentScopes,
        StudentAttributes;

    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = [
        'firstname',
        'middlename',
        'lastname',
        'idnumber',
        'email',
        'phone1',
        'phone2',
        'lastip'
    ];


}

trait StudentRelations
    {

    }
trait StudentScopes
    {

    }
trait StudentAttributes
    {

    }
