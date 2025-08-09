<?php

namespace Database\Factories\Moodle\Forum;
use App\Models\Moodle\Forum\Discussion;
use App\Models\Moodle\Forum\Forum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 */
class DiscussionFactory extends Factory
{
    protected  $model = Discussion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $forum=Forum::factory()->createOne();
        return [
            'forum'=>  $forum->id,
            'course'=>  $forum->course,
            'name' => fake('fa_IR')->unique()->sentence(),

        ];
    }

}
