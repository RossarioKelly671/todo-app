<?php

namespace Database\Factories\Task;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userCount = User::query()->count();
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'due_date' => $this->faker->date(),
            'status' => $this->faker->numberBetween(0, 2),
            'priority' => $this->faker->numberBetween(0, 2),
            'user_id' => $this->faker->numberBetween(1, $userCount),
        ];
    }
}
