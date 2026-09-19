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
    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="Get all tasks",
     *     tags={"Tasks"},
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks"
     *     )
     * )
     */
    public function __construct(
        private TaskService $taskService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();

        $tasks = $user->role === 'admin' ? Task::query() : $user->tasks();

        return TaskResource::collection($tasks->get());
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Create task",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Created")
     * )
     */
    public function store(CreateTaskRequest $request)
    {
        $dto = CreateTaskData::fromArray($request);
        $task = $this->taskService->createTask($dto);
        
        return new TaskResource($task);
    }

    /**
     * @OA\Get(
     * path="/tasks/{task}",
     * summary="Get task",
     * tags={"Tasks"},
     * 
     * @OA\Parameter(
     * name="task",
     * in="path",
     * required=true,
     * description="Task ID",
     * @OA\Schema(type="integer")
     * ),
     * 
     * @OA\Response(response=200, description="Task")
     * )
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * @OA\Post(
     *     path="/tasks/{task}",
     *     summary="Update task",
     *     tags={"Tasks"},
     *
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 maxLength=255,
     *                 example="Updated task"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 example="Updated description"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $dto = UpdateTaskData::fromArray($request);
        $updatedTask = $this->taskService->updateTask($task, $dto);

        return new TaskResource($updatedTask);
    }

    /**
     * @OA\Post(
     *     path="/tasks/{task}/complete",
     *     summary="Complete task",
     *     tags={"Tasks"},
     *
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Task completed successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function complete(Task $task)
    {
        $task = $this->taskService->completeTask($task);

        return new TaskResource($task);
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{task}",
     *     summary="Delete task",
     *     tags={"Tasks"},
     *
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Task deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function destroy(Task $task)
    {
        Task::destroy($task->id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
