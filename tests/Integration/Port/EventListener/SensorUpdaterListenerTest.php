<?php

declare(strict_types=1);

namespace Tests\Integration\Port\EventListener;

use App\Application\MeasurementCreatedEvent;
use App\Application\UpdateSensorCommand;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Tests\BaseKernelWithDBTestCase;
use Tests\Mocks\MessageBus;

final class SensorUpdaterListenerTest extends BaseKernelWithDBTestCase
{
    public function testOnMeasurementCreatedEvent(): void
    {
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = self::getContainer()->get(id: EventDispatcherInterface::class);

        /** @var MessageBus $messageBus */
        $messageBus = self::getContainer()->get(id: MessageBusInterface::class);

        $messageBus->messages = [];

        $eventDispatcher->dispatch(
            event: new MeasurementCreatedEvent(
                sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                co2: 1800,
                time: new \DateTime(),
            ),
        );

        $updateSensorCommand = $messageBus->getFirstMessageByType(
            UpdateSensorCommand::class,
        );

        self::assertSame(
            expected: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            actual: $updateSensorCommand->sensorId,
        );
    }
}
