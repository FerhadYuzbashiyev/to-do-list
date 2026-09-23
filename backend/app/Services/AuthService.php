<?php

namespace App\Services;

use App\Models\User;
use App\DTO\Auth\CreateUserData;
use App\DTO\Auth\UpdateUserData;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function checkEmailAndPassword(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->hashed_password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        return $user;
    }

    public function createUser(CreateUserData $data): User
    {
        return User::create([
            'username' => $data->username,
            'email' => $data->email,
            'hashed_password' => Hash::make($data->password),
            'is_verified' => $data->isVerified,
            'role' => 'user',
        ]);
    }

    public function updateUser(User $user, UpdateUserData $data): User
    {
        $payload = $data->toArray();

        $emailChanged = isset($payload['email'])
            && $payload['email'] !== $user->email;

        if (isset($payload['password'])) {
            $payload['hashed_password'] = Hash::make($payload['password']);
            unset($payload['password']);
        }

        if ($emailChanged) {
            $payload['is_verified'] = false;
        }

        $user->updateQuietly($payload);

        return $user->refresh();
    }

    public function deleteUser(User $user): void
    {
        User::destroy($user->id);
    }
}