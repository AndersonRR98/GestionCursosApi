<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Course;


class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
            $commentableModels = [
            Lesson::class => Lesson::inRandomOrder()->first() ?? Lesson::factory()->create(),
            Course::class => Course::inRandomOrder()->first() ?? Course::factory()->create(),
        ];

        $commentableType = $this->faker->randomElement(array_keys($commentableModels));
        $commentable = $commentableModels[$commentableType];

        return [
             'texto' => $this->faker->sentence(),
            'user_id' => User::inRandomOrder()->first()?->id,

            'commentable_id' => $commentable->id,
            'commentable_type' => $commentableType,
        ];
    }
}
