<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status->name,
            'priority' => $this->priority->name,
            'due_date' => $this->due_date,
            'created_at' => $this->created_at,
            'owner_id' => $this->user_id,
        ];
    }
}
