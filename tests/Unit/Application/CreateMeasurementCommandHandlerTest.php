<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\CreateMeasurementCommand;
use App\Application\CreateMeasurementCommandHandler;
use App\Application\MeasurementCreatedEvent;
use App\Application\MeasurementWriteRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

final class CreateMeasurementCommandHandlerTest extends TestCase
{
    public function testHandler(): void
    {
        $measurementWriteRepository = $this->createMock(originalClassName: MeasurementWriteRepositoryInterface::class);
        $measurementWriteRepository
            ->expects(self::once())
            ->method(constraint: 'create')
        ;

        $eventDispatcher = $this->createMock(originalClassName: EventDispatcherInterface::class);
        $eventDispatcher
            ->expects(self::once())
            ->method(constraint: 'dispatch')
            ->with(self::callback(callback: function (MeasurementCreatedEvent $event): bool {
                return '5218eb04-9da6-4dd5-a780-cd50f9378ff6' === $event->sensorId &&
                    2000 === $event->co2 &&
                    $event->time->getTimestamp() === \strtotime(datetime: '2022-09-10 18:36:01');
            }))
        ;

        $handler = new CreateMeasurementCommandHandler(
            measurementWriteRepository: $measurementWriteRepository,
            eventDispatcher: $eventDispatcher,
        );

        $handler->__invoke(
            command: new CreateMeasurementCommand(
                sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                co2: 2000,
                time: new \DateTime(datetime: '2022-09-10 18:36:01'),
            ),
        );
    }
}
