<?php

declare(strict_types=1);

namespace Tests\Unit\Port\EventListener;

use App\Application\CreateSensorCommand;
use App\Application\MeasurementCreatedEvent;
use App\Application\SensorReadRepositoryInterface;
use App\Application\UpdateSensorCommand;
use App\Port\EventListener\SensorUpdaterListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class SensorUpdaterListenerTest extends TestCase
{
    public function testOnMeasurementCreatedEventSensorExists(): void
    {
        $commandBus = new class () implements MessageBusInterface {
            public array $commands;

            public function dispatch(
                object $message,
                array $stamps = [],
            ): Envelope {
                $this->commands[] = $message;

                return new Envelope(message: $message);
            }
        };

        $sensorRepository = $this->createMock(originalClassName: SensorReadRepositoryInterface::class);
        $sensorRepository
            ->expects(self::once())
            ->method(constraint: 'exists')
            ->with('5218eb04-9da6-4dd5-a780-cd50f9378ff6')
            ->willReturn(value: true)
        ;

        $listener = new SensorUpdaterListener(
            commandBus: $commandBus,
            sensorRepository: $sensorRepository,
        );

        $listener->onMeasurementCreatedEvent(
            event: new MeasurementCreatedEvent(
                sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                co2: 1800,
                time: new \DateTime(),
            ),
        );

        self::assertCount(
            expectedCount: 1,
            haystack: $commandBus->commands,
        );

        $updateSensorCommand = $commandBus->commands[0];

        self::assertInstanceOf(
            expected: UpdateSensorCommand::class,
            actual: $updateSensorCommand,
        );

        self::assertSame(
            expected: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            actual: $updateSensorCommand->sensorId,
        );
    }

    public function testOnMeasurementCreatedEventSensorDoesNotExist(): void
    {
        $commandBus = new class () implements MessageBusInterface {
            public array $commands;

            public function dispatch(
                object $message,
                array $stamps = [],
            ): Envelope {
                $this->commands[] = $message;

                return new Envelope(message: $message);
            }
        };

        $sensorRepository = $this->createMock(originalClassName: SensorReadRepositoryInterface::class);
        $sensorRepository
            ->expects(self::once())
            ->method(constraint: 'exists')
            ->with('5218eb04-9da6-4dd5-a780-cd50f9378ff6')
            ->willReturn(value: false)
        ;

        $listener = new SensorUpdaterListener(
            commandBus: $commandBus,
            sensorRepository: $sensorRepository,
        );

        $listener->onMeasurementCreatedEvent(
            event: new MeasurementCreatedEvent(
                sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                co2: 1800,
                time: new \DateTime(),
            ),
        );

        self::assertCount(
            expectedCount: 2,
            haystack: $commandBus->commands,
        );

        $createSensorCommand = $commandBus->commands[0];

        self::assertInstanceOf(
            expected: CreateSensorCommand::class,
            actual: $createSensorCommand,
        );

        self::assertSame(
            expected: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            actual: $createSensorCommand->sensorId,
        );

        $updateSensorCommand = $commandBus->commands[1];

        self::assertInstanceOf(
            expected: UpdateSensorCommand::class,
            actual: $updateSensorCommand,
        );

        self::assertSame(
            expected: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            actual: $updateSensorCommand->sensorId,
        );
    }
}
