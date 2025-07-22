<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
            'password' => ['sometimes', 'string', 'min:6'],
            'role_id' => ['sometimes', 'integer', 'exists:roles,id'],
        ];
    }
}