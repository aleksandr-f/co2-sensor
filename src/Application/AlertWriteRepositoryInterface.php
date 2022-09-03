<?php

declare(strict_types=1);

namespace App\Application;

interface AlertWriteRepositoryInterface
{
    /**
     * @param int[] $measurements
     */
    public function create(
        string $sensorId,
        array $measurements,
        \DateTimeInterface $startTime = new \DateTime(),
    ): void;

    public function updateEndTime(
        string $sensorId,
        \DateTimeInterface $endTime = new \DateTime(),
    ): void;
}
