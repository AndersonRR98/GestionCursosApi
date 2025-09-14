<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
                'nombre' => $this->faker->randomElement(['Admin', 'Instructor', 'Estudiante']),
                'permisos' => json_encode([
                'crear_cursos' => true,
                'editar_cursos' => false,
                'eliminar_cursos' => false,
            ]),
        ];
    }
}
