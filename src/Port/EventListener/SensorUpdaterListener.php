<?php

declare(strict_types=1);

namespace App\Port\EventListener;

use App\Application\CreateSensorCommand;
use App\Application\MeasurementCreatedEvent;
use App\Application\SensorReadRepositoryInterface;
use App\Application\UpdateSensorCommand;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class SensorUpdaterListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly SensorReadRepositoryInterface $sensorRepository,
    ) {
    }

    #[ArrayShape([MeasurementCreatedEvent::class => 'string'])]
    public static function getSubscribedEvents(): array
    {
        return [
            MeasurementCreatedEvent::class => 'onMeasurementCreatedEvent',
        ];
    }

    public function onMeasurementCreatedEvent(
        MeasurementCreatedEvent $event,
    ): void {
        if (
            !$this->sensorRepository->exists(
                id: $event->sensorId,
            )
        ) {
            $this->commandBus->dispatch(new CreateSensorCommand(
                sensorId: $event->sensorId,
            ));
        }

        $this->commandBus->dispatch(new UpdateSensorCommand(
            sensorId: $event->sensorId,
        ));
    }
}
