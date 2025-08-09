<?php

namespace Database\Factories\Moodle;

use App\Models\Moodle\Course;
use App\Models\Moodle\CourseModule;
use App\Models\Moodle\QuizLog;
use App\Models\Moodle\Student;
use App\Models\Moodle\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 */
class QuizLogFactory extends Factory
{
    protected $model = QuizLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $module=CourseModule::factory()->createOne();
        return [
            'courseid' =>$module->course,
            "quizid" => $module->id,
            "userid" => Student::factory()->createOne()->id,
            "webcampicture" => "http://127.0.0.1/pluginfile.php/21/quizaccess_proctoring/picture/".fake()->image(),
            "status" => 3,
            "awsscore" => 0,
            "awsflag" => 0,
            "timemodified" => 1683125471,
        ];
    }


}
