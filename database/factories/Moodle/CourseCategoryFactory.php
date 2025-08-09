<?php

namespace Database\Factories\Moodle;

use App\Enums\MoodleRoleEnum;
use App\Enums\RoleEnum;
use App\Models\Moodle\Course;
use App\Models\Moodle\CourseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * @extends Factory<CourseCategory>
 */
class CourseCategoryFactory extends Factory
{
    protected  $model = CourseCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->jobTitle(),
            'description'=>fake()->sentence(),
            'idnumber'=>fake()->unique()->randomDigit(),
        ];
    }


}
