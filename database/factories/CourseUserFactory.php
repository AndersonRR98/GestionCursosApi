<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Course;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseUser>
 */
class CourseUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

             'progreso' => $this->faker->numberBetween(0, 100),
            'completado' => $this->faker->boolean(50),
            'user_id' => User::inRandomOrder()->first()?->id,
            'course_id' => Course::inRandomOrder()->first()?->id,
        ];
    }
}
