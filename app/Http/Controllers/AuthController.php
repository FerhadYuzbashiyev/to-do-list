<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\DTO\Auth\CreateUserData;
use App\DTO\Auth\UpdateUserData;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\AuthService;
use App\Services\OtpService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    public function __construct(
        private OtpService $otpService,
        private AuthService $authService,
        private SubscriptionService $subscriptionService
    ){}
    
    /**
     * @OA\Get(
     *   path="/auth/me",
     *   tags={"Auth"},
     *   summary="Get current authenticated user",
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=200, description="Current user profile"),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function getUser(Request $request)
    {
        $user = $request->user();
        return new UserResource($user);
    }
    /**
     * @OA\Post(
     *   path="/auth/register",
     *   tags={"Auth"},
     *   summary="Register user and send OTP",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"username","first_name","last_name","email","password","phone_number"},
     *       @OA\Property(property="username", type="string"),
     *       @OA\Property(property="first_name", type="string"),
     *       @OA\Property(property="last_name", type="string"),
     *       @OA\Property(property="email", type="string", format="email"),
     *       @OA\Property(property="password", type="string", format="password"),
     *       @OA\Property(property="phone_number", type="string")
     *     )
     *   ),
     *   @OA\Response(response=201, description="OTP sent")
     * )
     */
    public function register(RegisterRequest $request)
    {
        $dto = CreateUserData::fromArray($request->validated());
        $user = $this->authService->createUser($dto);
        $this->subscriptionService->setBasicSubscription($user);
        $this->otpService->create_otp_and_send($user);

        return response()->json([
            'message' => 'OTP sent to your email',
            'user_id' => $user->id
        ], Response::HTTP_CREATED);
    }
    /**
     * @OA\Post(
     *   path="/auth/login",
     *   tags={"Auth"},
     *   summary="Login and send OTP",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email"),
     *       @OA\Property(property="password", type="string", format="password")
     *     )
     *   ),
     *   @OA\Response(response=200, description="OTP sent"),
     *   @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = $this->authService->checkEmailAndPassword(
                $request->email,
                $request->password
            );
        } catch (ValidationException $e) {
            return response()->json($e->errors(), Response::HTTP_UNAUTHORIZED);
        }

        $this->otpService->create_otp_and_send($user);

        return response()->json([
            'message' => 'OTP sent to your email'
        ], Response::HTTP_OK);
    }
    /**
     * @OA\Post(
     *   path="/auth/logout",
     *   tags={"Auth"},
     *   summary="Logout current user and revoke current token",
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=200, description="Logged out"),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function logout(Request $request)
    {
        $suspectToken = $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out successfully!',
            'deleted token' => $suspectToken
        ], Response::HTTP_OK);
    }
    /**
     * @OA\Post(
     *   path="/auth/update",
     *   tags={"Auth"},
     *   summary="Update current user",
     *   security={{"sanctum":{}}},
     *   @OA\RequestBody(
     *     required=false,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         @OA\Property(property="username", type="string"),
     *         @OA\Property(property="first_name", type="string"),
     *         @OA\Property(property="last_name", type="string"),
     *         @OA\Property(property="email", type="string", format="email"),
     *         @OA\Property(property="password", type="string", format="password"),
     *         @OA\Property(property="phone_number", type="string"),
     *         @OA\Property(property="image", type="string", format="binary")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=200, description="Updated user"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateUserRequest $request)
    {
        $dto = UpdateUserData::fromValidatedPayload($request->validated(), $request->file('image'));
        $updatedUser = $this->authService->updateUser($request->user(), $dto);
        return new UserResource($updatedUser); 
    }
    /**
     * @OA\Delete(
     *   path="/auth/destroy",
     *   tags={"Auth"},
     *   summary="Delete current user",
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=204, description="User deleted"),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy(Request $request)
    {
        $this->authService->deleteUser($request->user());
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    /**
     * @OA\Delete(
     *   path="/admin/auth/{user}",
     *   tags={"Auth"},
     *   summary="Admin deletes user",
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="user", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=204, description="User deleted"),
     *   @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function adminDestroy(User $user)
    {
        $this->authorize('delete', $user);
        $this->authService->deleteUser($user);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}