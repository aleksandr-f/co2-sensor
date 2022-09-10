<?php

declare(strict_types=1);

namespace App\Application;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateMeasurementCommandHandler
{
    public function __construct(
        private readonly MeasurementWriteRepositoryInterface $measurementWriteRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(CreateMeasurementCommand $command): void
    {
        $this->measurementWriteRepository->create(
            sensorId: $command->sensorId,
            co2: $command->co2,
            time: $command->time,
        );

        $this->eventDispatcher->dispatch(
            event: new MeasurementCreatedEvent(
                sensorId: $command->sensorId,
                co2: $command->co2,
                time: $command->time,
            ),
        );
    }
}
