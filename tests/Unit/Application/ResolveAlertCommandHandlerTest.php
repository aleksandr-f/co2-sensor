<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\AlertWriteRepositoryInterface;
use App\Application\ResolveAlertCommand;
use App\Application\ResolveAlertCommandHandler;
use PHPUnit\Framework\TestCase;

final class ResolveAlertCommandHandlerTest extends TestCase
{
    public function testHandler(): void
    {
        $repository = $this->createMock(originalClassName: AlertWriteRepositoryInterface::class);
        $repository
            ->expects(self::once())
            ->method(constraint: 'updateEndTime')
        ;

        $handler = new ResolveAlertCommandHandler(
            alertRepository: $repository,
        );

        $handler->__invoke(command: new ResolveAlertCommand(sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6'));
    }
}
