<?php

namespace App\DTO\Auth;

class UpdateUserData
{
    public function __construct(
        public ?string $username,
        public ?string $email,
    ) {}

    public static function fromValidatedPayload(array $data, ?UploadedFile $image): self
    {
        return new self(
            username: $data['username'] ?? null,
            email: $data['email'] ?? null,
        );
    }
    
    public function toArray(): array
    {
        return array_filter([
            'username' => $this->username,
            'email' => $this->email,
        ], fn($v) => $v !== null);
    }
}