<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\AlertWriteRepositoryInterface;
use App\Application\CreateAlertCommand;
use App\Application\CreateAlertCommandHandler;
use PHPUnit\Framework\TestCase;

final class CreateAlertCommandHandlerTest extends TestCase
{
    public function testHandler(): void
    {
        $alertRepository = $this->createMock(AlertWriteRepositoryInterface::class);
        $alertRepository
            ->expects(self::once())
            ->method('create')
            ->with(
                '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                [1800, 1800, 1800],
            )
        ;

        $handler = new CreateAlertCommandHandler(
            $alertRepository,
        );

        $handler->__invoke(
            new CreateAlertCommand(
                sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                measurements: [1800, 1800, 1800],
            ),
        );
    }
}
