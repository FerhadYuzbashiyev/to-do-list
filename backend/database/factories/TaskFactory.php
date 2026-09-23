<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'status' => Task::STATUS_ACTIVE
        ];
    }

    public function complete()
    {
        return $this->state([
            'status' => Task::STATUS_COMPLETED
        ]);
    }
}
