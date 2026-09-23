<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function delete(User $user, User $targetUser): bool
    {
        return $user->role === 'admin';
    }
}