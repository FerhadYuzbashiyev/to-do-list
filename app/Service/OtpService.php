<?php
namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function create_otp_and_send(User $user): void
    {
        Otp::where('user_id', $user->id)->delete();

        $code = random_int(1000, 9999);
        
        Otp::create([
            'user_id' => $user->id,
            'otp_code' => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new OtpMail($code));
    }
    
    public function verify_and_get_token(int $userId, string $code): string
    {
        $otp = Otp::where('user_id', $userId)
        ->where('otp_code', $code)
        ->where('expires_at', '>', now())
        ->firstOrFail();

        $otp->delete();

        $user = User::findOrFail($userId);
        $user->updateQuietly(['is_verified' => true]);
        // $expiresAt = Carbon::now()->addMinutes(30);
        // $token = $user->createToken('auth_token', ['*'], $expiresAt)->plainTextToken;
        $token = $user->createToken('auth_token')->plainTextToken;        
        return $token;
    }

}