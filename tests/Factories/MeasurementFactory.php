<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Application\MeasurementReadRepositoryInterface;
use App\Application\MeasurementWriteRepositoryInterface;

final class MeasurementFactory
{
    public function __construct(
        private readonly MeasurementWriteRepositoryInterface $measurementWriteRepository,
        private readonly MeasurementReadRepositoryInterface $measurementReadRepository,
    ) {
    }

    public function createOne(array $data = []): array
    {
        $sensorId = $data['sensorId'] ?? '5218eb04-9da6-4dd5-a780-cd50f9378ff6';

        $this->measurementWriteRepository->create(
            sensorId: $sensorId,
            co2: $data['co2'] ?? 1800,
            time: $data['time'] ?? new \DateTime(),
        );

        return $this->measurementReadRepository->getLastMeasurements(
            sensorId: $sensorId,
            count: 1,
        );
    }

    public function createMany(int $count, array $data = []): void
    {
        for ($i = 0; $i < $count; ++$i) {
            $this->createOne(data: $data);
        }
    }
}
