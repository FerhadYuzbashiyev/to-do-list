<?php

namespace App\DTO\Auth;

class CreateUserData
{
    public function __construct(
        public string $username,
        public string $email,
        public string $hashed_password,
        public bool $isVerified,
        public string $role,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            username: $data['username'],
            email: $data['email'],
            hashed_password: $data['hashed_password'],
            isVerified: (bool) ($data['is_verified'] ?? false),
            role: $data['role'] ?? 'user',
        );
    }
}