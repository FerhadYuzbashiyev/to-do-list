<?php
namespace App\Services;

use App\Models\User;
use App\DTO\Auth\CreateUserData;
use App\DTO\Auth\UpdateUserData;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function checkEmailAndPassword(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();

        if(!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials']
            ]);
        }
        return $user;
    }

    public function createUser(CreateUserData $data): User
    {
        return User::create([
            'username' => $data->username,
            'email' => $data->email,
            'hashed_password' => Hash::make($data->hashed_password),
            'is_verified' => $data->isVerified,
            'role' => $data->role,
        ]);
    }


    public function updateUser(User $user, UpdateUserData $data): User
    {
        $user->updateQuietly($data->toArray());
        $payload = $data->toArray();

        $user->updateQuietly($payload);
        
        return $user->refresh();
    }

    public function deleteUser(User $user): void
    {
        User::destroy($user->id);
    }
}