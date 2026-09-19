<?php

namespace App\Service;

use App\DTO\CreateTaskData;
use App\DTO\UpdateTaskData;
use App\Models\Task;
use App\Models\User;

class TaskService
{
    public function createTask(User $user, CreateTaskData $data): Task
    {
        return $user->tasks()->create([
            'title' => $data->title,
            'description' => $data->description,
            'status' => Task::STATUS_ACTIVE,
        ]);
    }

    public function updateTask(Task $task, UpdateTaskData $data): Task
    {
        $task->update($data->toArray());

        return $task->refresh();
    }

    public function completeTask(Task $task): Task
    {
        $task->update([
            'status' => Task::STATUS_COMPLETED,
        ]);

        return $task;
    }
}