<?php

declare(strict_types=1);

namespace App\Application;

interface MeasurementReadRepositoryInterface
{
    public function getLastMeasurements(
        string $sensorId,
        int $count,
    ): array;

    public function getMax(string $sensorId, int $days): int;

    public function getAvg(string $sensorId, int $days): float;
}
