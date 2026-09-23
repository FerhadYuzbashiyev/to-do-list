<?php

namespace App\OpenApi;

/**
 * @OA\Post(
 *     path="/auth/register",
 *     summary="Register user and send OTP",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username","email","password"},
 *             @OA\Property(
 *                 property="username",
 *                 type="string",
 *                 example="john_doe"
 *             ),
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 example="john@example.com"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 format="password",
 *                 example="password123"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="OTP sent"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/auth/login",
 *     summary="Login and send OTP",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 example="john@example.com"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 format="password",
 *                 example="password123"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OTP sent"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid credentials"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/auth/verify-otp",
 *     summary="Verify OTP and receive access token",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","code"},
 *             @OA\Property(
 *                 property="user_id",
 *                 type="integer",
 *                 example=1
 *             ),
 *             @OA\Property(
 *                 property="code",
 *                 type="string",
 *                 example="1234"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OTP verified successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Invalid or expired OTP"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/auth/me",
 *     summary="Get current authenticated user",
 *     tags={"Auth"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Current user profile"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/auth/logout",
 *     summary="Logout current user",
 *     tags={"Auth"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Logged out successfully"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 *
 * @OA\POST(
 *     path="/auth/update",
 *     summary="Update current user",
 *     tags={"Auth"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="username",
 *                 type="string",
 *                 example="new_username"
 *             ),
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 example="new@example.com"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 format="password",
 *                 example="newpassword123"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated"
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
 * @OA\Delete(
 *     path="/auth/delete",
 *     summary="Delete current user",
 *     tags={"Auth"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=204,
 *         description="User deleted"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/admin/users/{user}",
 *     summary="Admin deletes user",
 *     tags={"Auth"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="user",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="User deleted"
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
class Auth
{
}