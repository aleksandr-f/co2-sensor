<?php

declare(strict_types=1);

namespace App\Application;

interface MeasurementReadRepositoryInterface
{
    /** @return int[] */
    public function getLastMeasurements(
        string $sensorId,
        int $count,
    ): array;
}
