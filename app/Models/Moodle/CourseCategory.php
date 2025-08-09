<?php
namespace App\Models\Moodle;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


class CourseCategory extends _BaseLMSModel
{
    use HasFactory,
        CategoryRelations,
        CategoryScopes,
        CategoryAttributes;


    protected $table = 'course_categories';

    public $timestamps = false;
    protected $fillable = [
        'name',
        'description',
        'idnumber',
    ];


}

trait CategoryRelations
{
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class,'category','id');
    }
}

trait CategoryScopes
{

}

trait CategoryAttributes
{
}
