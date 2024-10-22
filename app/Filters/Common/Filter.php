<?php

namespace App\Filters\Common;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

abstract class Filter
{
    public const KEYS_TO_INT = [];
    protected Builder $builder;

    public function __construct(protected readonly FormRequest $request)
    {
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->input() as $method => $value) {
            $methodName = Str::camel($method);

            if (null === $value) {
                continue;
            }

            if (method_exists($this, $methodName)) {

                if (in_array($method, static::KEYS_TO_INT, true)) {
                    $value = (int)$value;
                }

                $this->builder = $this->{$methodName}($value);
            }
        }

        return $this->builder;
    }
}
