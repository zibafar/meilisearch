<?php

namespace Database\Factories\Moodle\Forum;
use App\Models\Moodle\Course;
use App\Models\Moodle\Forum\Forum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 */
class ForumFactory extends Factory
{
    protected  $model = Forum::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course'=>  Course::factory(),
            'intro' => fake('fa_IR')->unique()->realText(),

        ];
    }

}
