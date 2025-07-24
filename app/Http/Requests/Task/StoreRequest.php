<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization is handled in the controller
    }

    public function rules(): array
    {
        return [
            'title'           => 'required|string|max:255',
            'describe'        => 'nullable|string',
            'slug'            => 'nullable|string|unique:tasks,slug',
            'expiration_date' => 'nullable|date|after:today',
            'status'          => 'sometimes|string|in:pending,in_progress,completed,cancelled',
            'user_id'         => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'        => 'O título é obrigatório.',
            'title.max'             => 'O título deve ter no máximo 255 caracteres.',
            'describe.string'       => 'A descrição deve ser um texto válido.',
            'slug.unique'           => 'Este slug já está sendo usado por outra tarefa.',
            'expiration_date.date'  => 'A data de expiração deve ser uma data válida.',
            'expiration_date.after' => 'A data de expiração deve ser posterior a hoje.',
        ];
    }
}
