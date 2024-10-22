<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class CommonTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'description' => ['string'],
            'status' => ['integer'],
            'priority' => ['integer'],
            'due_date' => ['date'],
        ];
    }
}
