<?php

declare(strict_types=1);

namespace App\Application;

interface MeasurementWriteRepositoryInterface
{
    public function create(
        string $sensorId,
        int $co2,
        \DateTimeInterface $time,
    ): void;
}
