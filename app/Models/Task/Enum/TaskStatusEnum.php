<?php

namespace App\Models\Task\Enum;

enum TaskStatusEnum: int
{
    case WAITING = 0;
    case IN_PROGRESS = 1;
    case COMPLETED = 2;


    public static function getStatusByInt(int $status): ?self
    {
        return match ($status) {
            self::WAITING->value => self::WAITING,
            self::IN_PROGRESS->value => self::IN_PROGRESS,
            self::COMPLETED->value => self::COMPLETED,
            default => null,
        };
    }
}
