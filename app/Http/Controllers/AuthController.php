<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;

use App\DTO\Auth\CreateUserData;
use App\DTO\Auth\UpdateUserData;

use App\Http\Resources\User\UserResource;

use App\Models\User;

use App\Services\AuthService;
use App\Services\OtpService;

class AuthController extends Controller
{
    public function __construct(
        private OtpService $otpService,
        private AuthService $authService,
    ) {
    }

    public function getUser(Request $request)
    {
        $user = $request->user();

        return new UserResource($user);
    }

    public function register(RegisterRequest $request)
    {
        $dto = CreateUserData::fromArray($request->validated());

        $user = $this->authService->createUser($dto);

        $this->otpService->create_otp_and_send($user);

        return response()->json([
            'message' => 'OTP sent to your email',
            'user_id' => $user->id,
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = $this->authService->checkEmailAndPassword(
                $request->email,
                $request->password
            );
        } catch (ValidationException $e) {
            return response()->json(
                $e->errors(),
                Response::HTTP_UNAUTHORIZED
            );
        }

        $this->otpService->create_otp_and_send($user);

        return response()->json([
            'message' => 'OTP sent to your email',
            'user_id' => $user->id,
        ], Response::HTTP_OK);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $token = $this->otpService->verify_and_get_token(
            $request->user_id,
            $request->code
        );

        return response()->json([
            'message' => 'OTP verified successfully',
            'token' => $token,
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully!',
        ], Response::HTTP_OK);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();

        $oldEmail = $user->email;

        $dto = UpdateUserData::fromValidatedPayload($request->validated());

        $updatedUser = $this->authService->updateUser($user, $dto);

        if ($updatedUser->email !== $oldEmail) {
            $this->otpService->create_otp_and_send($updatedUser);
        }

        return new UserResource($updatedUser);
    }

    public function destroy(Request $request)
    {
        $this->authService->deleteUser($request->user());

        return response()->json(
            null,
            Response::HTTP_NO_CONTENT
        );
    }

    public function adminDestroy(User $user)
    {
        $this->authorize('delete', $user);

        $this->authService->deleteUser($user);

        return response()->json(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}