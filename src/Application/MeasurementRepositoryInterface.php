<?php

declare(strict_types=1);

namespace App\Application;

interface MeasurementRepositoryInterface
{
    public function create(
        string $sensorId,
        int $co2,
        \DateTimeInterface $time,
    ): void;

    /** @return int[] */
    public function getLastMeasurements(
        string $sensorId,
        int $count,
    ): array;
}
