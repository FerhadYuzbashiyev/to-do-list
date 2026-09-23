<?php

namespace App\DTO\Auth;

class UpdateUserData
{
    public function __construct(
        public ?string $username,
        public ?string $email,
        public ?string $password,
    ) {}

    public static function fromValidatedPayload(array $data): self
    {
        return new self(
            username: $data['username'] ?? null,
            email: $data['email'] ?? null,
            password: $data['password'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
        ], fn($v) => $v !== null);
    }
}