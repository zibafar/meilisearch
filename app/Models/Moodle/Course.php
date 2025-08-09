<?php
namespace App\Models\Moodle;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Course extends _BaseLMSModel
{
    use HasFactory,
        CourseRelations,
        CourseScopes,
        CourseAttributes;


    protected $table = 'course';

    public $timestamps = false;
    protected $fillable = [
        'category',
        'sortorder',
        'fullname',
        'shortname',
        'idnumber',
        'summary'
    ];


}

trait CourseRelations
{
    /**
     * @return BelongsTo
     */
    public function course_category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'category', 'id');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'course', 'id');
    }

    public function teachers(): Collection
    {
        $course_id = $this->id;
        $query = /** @lang SQL */
            "
            SELECT
              mdl_user.id,
              mdl_user.username,
              mdl_user.firstname,
              mdl_user.lastname
            FROM
              mdl_course
              LEFT OUTER JOIN mdl_context
                ON mdl_course.id = mdl_context.instanceid
                and mdl_context.contextlevel = '50'
              LEFT OUTER JOIN mdl_role_assignments
                ON mdl_context.id = mdl_role_assignments.contextid
                AND mdl_role_assignments.roleid = '3'
              LEFT OUTER JOIN mdl_user
                ON mdl_role_assignments.userid = mdl_user.id
            WHERE
                mdl_course.id = ?
            ";
        $userData = DB::connection('mysql_moodle')->select($query, [$course_id]);

        return User::query()->hydrate($userData);
    }


}

trait CourseScopes
{

}

trait CourseAttributes
{
    /**
     * Get the teachers' full name.
     */
    protected function getTeacherNamesAttribute() :string
    {
        return implode(' | ', $this->teachers()->map(fn($t) => $t->full_name)->toArray());
    }

    /**
     * Get the teachers' full name.
     */
    protected function getTeacherIdsAttribute() :array
    {
        return  $this->teachers()->map(fn($t) => $t->id)->toArray();
    }
}
