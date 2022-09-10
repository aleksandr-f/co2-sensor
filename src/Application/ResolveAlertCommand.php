<?php

declare(strict_types=1);

namespace App\Application;

final class ResolveAlertCommand
{
    public function __construct(
        public readonly string $sensorId,
    ) {
    }
}
