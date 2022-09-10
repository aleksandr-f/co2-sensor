<?php

declare(strict_types=1);

namespace App\Application;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class UpdateSensorCommandHandler
{
    public function __construct(
        private readonly MeasurementReadRepositoryInterface $measurementReadRepository,
        private readonly SensorReadRepositoryInterface $sensorReadRepository,
        private readonly SensorWriteRepositoryInterface $sensorWriteRepository,
    ) {
    }

    public function __invoke(UpdateSensorCommand $command): void
    {
        $lastMeasurements = $this->measurementReadRepository->getLastMeasurements(
            sensorId: $command->sensorId,
            count: 3,
        );

        if (empty($lastMeasurements)) {
            return;
        }

        $sensor = $this->sensorReadRepository->get(id: $command->sensorId);

        $co2Values = [];

        foreach ($lastMeasurements as $measurement) {
            $co2Values[] = $measurement['co2'];
        }

        $sensor->updateStatus(measurements: $co2Values);

        $this->sensorWriteRepository->save(sensor: $sensor);
    }
}
