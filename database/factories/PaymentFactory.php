<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Course;



/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'monto' => $this->faker->randomFloat(2, 10, 500),
            'metodo_pago' => $this->faker->randomElement(['credit_card','paypal']),
            'estado' => $this->faker->boolean(50),
             'user_id' => User::inRandomOrder()->first()?->id,
            'course_id' => Course::inRandomOrder()->first()?->id,
        ];
    }
}
