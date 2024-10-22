<?php

namespace App\Filters\Common;

use Illuminate\Database\Eloquent\Builder;

trait HasFilter
{

    public function scopeFilter(Builder $builder, Filter $filter): Builder
    {
        return $filter->apply($builder);
    }
}
