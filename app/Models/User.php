<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Otp;
use App\Models\Task;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'hashed_password',
        'role',
        'is_verified',
    ];

    protected $hidden = ['hashed_password'];

    public function otps(): HasMany
    {
        return $this->hasMany(Otp::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}