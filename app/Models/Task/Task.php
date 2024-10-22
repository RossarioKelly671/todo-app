<?php

namespace App\Models\Task;

use App\Filters\Common\HasFilter;
use App\Models\Task\Enum\TaskPriorityEnum;
use App\Models\Task\Enum\TaskStatusEnum;
use App\Models\User;
use App\Sorts\HasSortableColumn;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory, HasFilter, HasSortableColumn;

    protected $perPage = 10;

    protected $guarded = ['id'];

    public $sortable = ['due_date', 'created_at'];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function status(): Attribute
    {
        return Attribute::get(
            fn ($value) => TaskStatusEnum::from($this->attributes['status'] ?? $value)
        );
    }

    protected function priority(): Attribute
    {
        return Attribute::get(
            fn ($value) => TaskPriorityEnum::from($this->attributes['priority'] ?? $value)
        );
    }

}
