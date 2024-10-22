<?php

namespace App\Filters;

use App\Filters\Common\Filter;
use Illuminate\Database\Eloquent\Builder;

class TaskFilter extends Filter
{

    protected function status(int $value): Builder
    {
        return $this->builder->where('status', $value);
    }

    protected function priority(int $value): Builder
    {
        return $this->builder->where('priority', $value);
    }
}
