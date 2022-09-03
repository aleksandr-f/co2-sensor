<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Application\CreateSensorCommand;
use App\Application\SensorReadRepositoryInterface;
use App\Domain\Sensor;
use Symfony\Component\Messenger\MessageBusInterface;

final class SensorFactory
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly SensorReadRepositoryInterface $sensorRepository,
    ) {
    }

    public function createOne(array $data): Sensor
    {
        $id = $data['id'] ?? 'fe7e5baf-f9d9-41ef-a2ba-9ebfb4cc4667';

        $this->messageBus->dispatch(new CreateSensorCommand(
            sensorId: $id,
        ));

        return $this->sensorRepository->get(id: $id);
    }
}
