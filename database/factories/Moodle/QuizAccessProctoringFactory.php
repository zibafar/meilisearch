<?php

namespace Database\Factories\Moodle;

use App\Models\Moodle\Quiz;
use App\Models\Moodle\QuizAccessProctoring;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 */
class QuizAccessProctoringFactory extends Factory
{
    protected  $model = QuizAccessProctoring::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quizid'=>  Quiz::factory(),
            'proctoringrequired'=>1
        ];
    }




}
