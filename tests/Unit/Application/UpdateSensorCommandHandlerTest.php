<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\MeasurementReadRepositoryInterface;
use App\Application\SensorReadRepositoryInterface;
use App\Application\SensorWriteRepositoryInterface;
use App\Application\UpdateSensorCommand;
use App\Application\UpdateSensorCommandHandler;
use App\Domain\Sensor;
use PHPUnit\Framework\TestCase;

final class UpdateSensorCommandHandlerTest extends TestCase
{
    public function testNotEnoughMeasurements(): void
    {
        $measurementReadRepository = $this->createMock(originalClassName: MeasurementReadRepositoryInterface::class);
        $measurementReadRepository
            ->expects(self::once())
            ->method(constraint: 'getLastMeasurements')
            ->with(
                '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                3,
            )
            ->willReturn(value: [])
        ;

        $sensorReadRepository = $this->createMock(originalClassName: SensorReadRepositoryInterface::class);
        $sensorReadRepository
            ->expects(self::never())
            ->method(constraint: 'get')
        ;

        $sensorWriteRepository = $this->createMock(originalClassName: SensorWriteRepositoryInterface::class);
        $sensorWriteRepository
            ->expects(self::never())
            ->method(constraint: 'save')
        ;

        $handler = new UpdateSensorCommandHandler(
            measurementReadRepository: $measurementReadRepository,
            sensorReadRepository: $sensorReadRepository,
            sensorWriteRepository: $sensorWriteRepository,
        );

        $handler->__invoke(
            command: new UpdateSensorCommand(sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6'),
        );
    }

    public function testSensorUpdated(): void
    {
        $measurementReadRepository = $this->createMock(originalClassName: MeasurementReadRepositoryInterface::class);
        $measurementReadRepository
            ->expects(self::once())
            ->method(constraint: 'getLastMeasurements')
            ->with(
                '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                3,
            )
            ->willReturn(value: [
                [
                    'sensorId' => '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                    'co2' => 2010,
                    'time' => '2022-09-10 19:58:01',
                ],
                [
                    'sensorId' => '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                    'co2' => 1800,
                    'time' => '2022-09-10 19:58:01',
                ],
                [
                    'sensorId' => '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                    'co2' => 2020,
                    'time' => '2022-09-10 19:58:01',
                ],
            ])
        ;

        $sensor = $this->createMock(Sensor::class);
        $sensor
            ->expects(self::once())
            ->method('updateStatus')
            ->with(
                [2010, 1800, 2020],
            )
        ;

        $sensorReadRepository = $this->createMock(originalClassName: SensorReadRepositoryInterface::class);
        $sensorReadRepository
            ->expects(self::once())
            ->method(constraint: 'get')
            ->with('5218eb04-9da6-4dd5-a780-cd50f9378ff6')
            ->willReturn($sensor)
        ;

        $sensorWriteRepository = $this->createMock(originalClassName: SensorWriteRepositoryInterface::class);
        $sensorWriteRepository
            ->expects(self::once())
            ->method(constraint: 'save')
            ->with($sensor)
        ;

        $handler = new UpdateSensorCommandHandler(
            measurementReadRepository: $measurementReadRepository,
            sensorReadRepository: $sensorReadRepository,
            sensorWriteRepository: $sensorWriteRepository,
        );

        $handler->__invoke(
            command: new UpdateSensorCommand(sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6'),
        );
    }
}
