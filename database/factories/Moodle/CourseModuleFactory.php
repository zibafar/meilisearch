<?php

namespace Database\Factories\Moodle;

use App\Models\Moodle\Course;
use App\Models\Moodle\CourseModule;
use App\Models\Moodle\Quiz;
use App\Models\Moodle\QuizAccessProctoring;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 */
class CourseModuleFactory extends Factory
{
    protected $model = CourseModule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $course = Course::factory()->createOne();
        $quiz = Quiz::factory(['course' => $course->id])->viaWebcam()->createOne();
        return [
            'course' => $course->id,
            "module" => 18, //quiz
            "instance" => $quiz->id,
            "section" => 1,
            "idnumber" => "",
            "added" => 1682779638,
            "score" => 0,
            "indent" => 0,
            "visible" => 1,
            "visibleoncoursepage" => 1,
            "visibleold" => 1,
            "groupmode" => 0,
            "groupingid" => 0,
            "completion" => 1,
            "completiongradeitemnumber" => null,
            "completionview" => 0,
            "completionexpected" => 0,
            "completionpassgrade" => 0,
            "showdescription" => 0,
            "availability" => null,
            "deletioninprogress" => 0,
            "downloadcontent" => 1,
            "lang" => "",
        ];
    }


}
