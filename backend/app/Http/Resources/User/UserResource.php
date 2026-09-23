<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            'is_verified' => $this->is_verified
        ];
    }
}