<?php

namespace Database\Factories\Moodle;

use App\Models\Moodle\Course;
use App\Models\Moodle\Quiz;
use App\Models\Moodle\QuizAccessProctoring;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 */
class QuizFactory extends Factory
{
    protected  $model = Quiz::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course'=>  Course::factory(),
            'name' =>fake()->unique()->word(),
            'intro' => fake()->unique()->word(),
            'introformat'=>1,
            'timeopen'=>now()->addMinutes(2)->timestamp,
            'timeclose'=>now()->addHours(3)->timestamp,
            'timelimit'=>0,
        ];
    }

    public function viaWebcam(): QuizFactory
    {

        return $this
            ->afterCreating(function (Quiz $quiz) {
                QuizAccessProctoring::factory(['quizid'=>$quiz->id])->createOne();
            })
            ;
    }




}
