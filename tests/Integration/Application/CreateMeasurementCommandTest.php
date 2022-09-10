<?php

declare(strict_types=1);

namespace Tests\Integration\Application;

use App\Application\CreateMeasurementCommand;
use App\Application\MeasurementCreatedEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Tests\BaseKernelWithDBTestCase;
use Tests\Mocks\EventDispatcher;

final class CreateMeasurementCommandTest extends BaseKernelWithDBTestCase
{
    public function testCommand(): void
    {
        /** @var MessageBusInterface $messageBus */
        $messageBus = self::getContainer()->get(id: MessageBusInterface::class);

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = self::getContainer()->get(id: EventDispatcher::class);

        self::assertEmpty($eventDispatcher->events);

        $messageBus->dispatch(
            new CreateMeasurementCommand(
                sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                co2: 2000,
                time: new \DateTime(datetime: '2022-09-10 18:36:01'),
            ),
        );

        /** @var MeasurementCreatedEvent $event */
        $event = $eventDispatcher->getFirstEventByType(type: MeasurementCreatedEvent::class);

        self::assertSame(
            '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            $event->sensorId,
        );

        self::assertSame(
            2000,
            $event->co2,
        );

        self::assertSame(
            \strtotime('2022-09-10 18:36:01'),
            $event->time->getTimestamp(),
        );
    }
}
