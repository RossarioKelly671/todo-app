<?php

namespace App\Models\Task\Enum;

enum TaskPriorityEnum: int
{
    case LOW = 0;
    case MIDDLE = 1;
    case HIGH = 2;

}
