<?php

declare(strict_types=1);

namespace App\Domain;

enum SensorStatusEnum: string
{
    case OK = 'OK';
    case WARN = 'WARN';
    case ALERT = 'ALERT';
}
