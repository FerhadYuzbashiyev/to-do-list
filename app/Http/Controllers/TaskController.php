<?php

namespace App\Http\Controllers;

use App\DTO\CreateTaskData;
use App\DTO\UpdateTaskData;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Service\TaskService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();

        $tasks = $user->role === 'admin'
            ? Task::query()
            : $user->tasks();

        return TaskResource::collection($tasks->get());
    }

    public function store(CreateTaskRequest $request)
    {
        $this->authorize('create', Task::class);

        $dto = CreateTaskData::fromArray($request);

        $task = $this->taskService->createTask(
            $request->user(),
            $dto
        );

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $dto = UpdateTaskData::fromArray($request);

        $updatedTask = $this->taskService->updateTask(
            $task,
            $dto
        );

        return new TaskResource($updatedTask);
    }

    public function complete(Task $task)
    {
        $this->authorize('complete', $task);

        $task = $this->taskService->completeTask($task);

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}