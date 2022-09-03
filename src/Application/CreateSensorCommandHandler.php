<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Sensor;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateSensorCommandHandler
{
    public function __construct(
        private readonly SensorRepositoryInterface $sensorRepository,
    ) {
    }

    public function __invoke(CreateSensorCommand $command): void
    {
        $sensor = new Sensor(id: $command->sensorId);

        $this->sensorRepository->save(sensor: $sensor);
    }
}
