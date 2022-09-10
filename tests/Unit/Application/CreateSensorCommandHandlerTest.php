<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\CreateSensorCommand;
use App\Application\CreateSensorCommandHandler;
use App\Application\SensorWriteRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class CreateSensorCommandHandlerTest extends TestCase
{
    public function testHandler(): void
    {
        $repository = $this->createMock(originalClassName: SensorWriteRepositoryInterface::class);
        $repository
            ->expects(self::once())
            ->method(constraint: 'save')
        ;

        $handler = new CreateSensorCommandHandler(
            sensorRepository: $repository,
        );

        $handler->__invoke(command: new CreateSensorCommand(sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6'));
    }
}
