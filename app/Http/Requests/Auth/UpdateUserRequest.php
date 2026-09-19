<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = $this->user()->id;
        return [
            'username' => ['sometimes', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($userId)],
        ];
    }
}