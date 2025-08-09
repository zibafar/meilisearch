<?php

namespace App\Models\Moodle;


use App\Models\Role;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use LaracraftTech\LaravelDateScopes\DateScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Scopes\TypeScope;
use Laravel\Passport\HasApiTokens;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use
        Notifiable,
        UserRelations,
//        UserScopes,
        Searchable,
        UserAttributes;

    protected $connection = 'mysql_moodle';
    protected $table = 'user';

    public $timestamps = false;
    protected string $guard_name = 'api';
    const NOTIFICATION_CHANNEL_MAIL = 'mail';

    const NOTIFICATION_CHANNEL_SMS = 'sms';


    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'idnumber',
        'email',
        'phone1',
        'phone2',
        'lastip',
        'address',
        'city',
        'description',
        'password'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        "firstaccess" => 'datetime:Y-m-d H:i',
        "lastaccess" => 'datetime:Y-m-d H:i',
        "lastlogin" => 'datetime:Y-m-d H:i',
        "currentlogin" => 'datetime:Y-m-d H:i',
        "timemodified" => 'datetime:Y-m-d H:i',
    ];

//    public function searchable(): bool
//    {
//        return $this->firstname || $this->lastname;
//    }

    public function toSearchableArray(): array
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
        ];
    }



    /**
     * Find the user instance for the given username.
     *
     * @param string $text
     *
     * @return User
     */
    public function findForPassport(string $text): User
    {
        return $this->where('username', $text)->first();
    }

//    public function newEloquentBuilder($query)
//    {
//        return new UserBuilder($query);
//    }

    public function owns(Quiz $model)
    {
        return $this->isAdmin() || $this->isSupervisor() || in_array($this->id, $model->course->teacher_ids);
    }

    public function syncRolesMdlToSe(): void
    {
        $names = $this->exam_roles->pluck('name');
        foreach ($names as $name) {
            \Spatie\Permission\Models\Role::query()->firstOrCreate([
                'name' => $name,
                'guard_name' => 'api',
            ]);
        }
        $this->syncRoles($names);

    }
}

trait UserRelations
{
    public function moodle_roles(): BelongsToMany
    {
        return $this->belongsToMany(MdlRole::class, 'role_assignments', 'userid', 'roleid');
    }

    public function image()
    {
        return $this->hasOne(MdlUserImage::class, 'user_id', 'id');
    }


}

//trait UserScopes
//    {
//
//    }
trait UserAttributes
{
    /**
     * Get the user's  roles in exam database.
     */
    protected function examRoles(): Attribute
    {
        $ids = $this->moodle_roles->pluck('id')->toArray();
        $names = array_unique(array_map("mappingRole", $ids));
        return Attribute::make(
            get: fn() => Role::query()->whereIn('name', $names)->get(),
        );
    }

    public function stateByQuiz(int $quizid)
    {
        return Attempt::query()->where('quiz', $quizid)->where('userid', $this->id)->value('state');
    }

    /**
     * Get the user's full name.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->firstname} {$this->lastname}"
        );
    }

    /**
     * Get the user's image.
     */
    protected function imgName(): Attribute
    {
        $file = @$this->image->file;
        return Attribute::make(
            get: fn() => $file == null ? null : Storage::disk('mdl')->url(get_mdlfile_path($file->contenthash))
        );
    }


}
