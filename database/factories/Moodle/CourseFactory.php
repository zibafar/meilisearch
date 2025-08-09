<?php

namespace Database\Factories\Moodle;

use App\Models\Moodle\Course;
use App\Models\Moodle\CourseCategory;
use App\Models\Moodle\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 */
class CourseFactory extends Factory
{
    protected  $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category'=>  CourseCategory::factory(),
            'sortorder'=>fake()->unique()->randomDigit(),
            'fullname' =>fake()->word(),
            'shortname'=>fake()->word(),
            'idnumber'=>fake()->randomDigit(),
            'summary'=>fake()->sentence(),
        ];
    }

    public function assignTeacherFor(User $user): CourseFactory
    {

        return $this
            ->afterCreating(function (Course $course) use($user) {
               $cx= DB::connection("mysql_moodle")
                    ->table('context')->insertGetId(
                        [
                            'contextlevel'=>50,
                            'instanceid' =>$course->id
                        ]
                    );
                DB::connection("mysql_moodle")
                    ->table('role_assignments')->insert(
                        [
                            'contextid'=>$cx,
                            'roleid' =>3,
                            'userid' =>$user->id
                        ]
                    );

            });
    }


}
