<?php

declare(strict_types=1);

namespace App\Application;

final class CreateMeasurementCommand
{
    public function __construct(
        public readonly string $sensorId,
        public readonly int $co2,
        public readonly \DateTimeInterface $time,
    ) {
    }
}
