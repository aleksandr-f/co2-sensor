<?php

declare(strict_types=1);

namespace App\Application;

interface MeasurementReadRepositoryInterface
{
    public function getLastMeasurements(
        string $sensorId,
        int $count,
    ): array;
}
