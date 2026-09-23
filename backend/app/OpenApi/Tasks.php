<?php

namespace App\OpenApi;

/**
 * @OA\Get(
 *     path="/tasks",
 *     summary="Get tasks",
 *     tags={"Tasks"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of tasks"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/tasks",
 *     summary="Create task",
 *     tags={"Tasks"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title","description"},
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 maxLength=255
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Task created"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/tasks/{task}",
 *     summary="Get task",
 *     tags={"Tasks"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="task",
 *         in="path",
 *         required=true,
 *         description="Task ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Task"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Task not found"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/tasks/{task}",
 *     summary="Update task",
 *     tags={"Tasks"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="task",
 *         in="path",
 *         required=true,
 *         description="Task ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 maxLength=255
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Task updated successfully"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
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
 *
 * @OA\Post(
 *     path="/tasks/{task}/complete",
 *     summary="Complete task",
 *     tags={"Tasks"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="task",
 *         in="path",
 *         required=true,
 *         description="Task ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Task completed successfully"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Task not found"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/tasks/{task}",
 *     summary="Delete task",
 *     tags={"Tasks"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="task",
 *         in="path",
 *         required=true,
 *         description="Task ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Task deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Task not found"
 *     )
 * )
 */
class Tasks
{
}