<?php

declare(strict_types=1);

namespace App\Application;

final class CreateAlertCommand
{
    /** @param int[] $measurements */
    public function __construct(
        public readonly string $sensorId,
        public readonly array $measurements,
    ) {
    }
}
