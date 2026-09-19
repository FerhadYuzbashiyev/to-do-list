<?php

namespace App\Http\Controllers;

use App\Http\Requests\OTP\CreateOTPRequest;
use App\Services\OtpService;
use Symfony\Component\HttpFoundation\Response;

class OtpController extends Controller
{
    public function __construct(
        private OtpService $otpService
    ){}
    /**
     * @OA\Post(
     *   path="/otp/verify",
     *   tags={"OTP"},
     *   summary="Verify OTP and return token",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"user_id","otp_code"},
     *       @OA\Property(property="user_id", type="integer"),
     *       @OA\Property(property="otp_code", type="integer"),
     *     )
     *   ),
     *   @OA\Response(response=201, description="Token created"),
     *   @OA\Response(response=401, description="Wrong credentials"),
     * )
     */
    public function verify(CreateOTPRequest $request)
    {
        $token = $this->otpService->verify_and_get_token(
            $request->user_id,
            $request->otp_code
        );

        return response()->json([
            'message' => 'Account verified successfully!',
            'token' => $token
        ], Response::HTTP_OK);
    }

}