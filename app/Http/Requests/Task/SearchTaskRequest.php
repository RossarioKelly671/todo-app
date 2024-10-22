<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class SearchTaskRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'status' => ['integer'],
            'priority' => ['integer'],
            'sort' => ['string']
        ];
    }
}
