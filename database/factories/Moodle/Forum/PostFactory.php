<?php

namespace Database\Factories\Moodle\Forum;
use App\Models\Moodle\Course;
use App\Models\Moodle\Forum\Discussion;
use App\Models\Moodle\Forum\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 */
class PostFactory extends Factory
{
    protected  $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'discussion'=>  Discussion::factory(),
            'subject' => fake('fa_IR')->unique()->realText(),
            'message' => fake('fa_IR')->unique()->realText(),
            'userid' => '2',

        ];
    }

}
