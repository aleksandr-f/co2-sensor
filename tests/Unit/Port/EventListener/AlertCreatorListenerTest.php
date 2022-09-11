<?php

declare(strict_types=1);

namespace Tests\Unit\Port\EventListener;

use App\Application\CreateAlertCommand;
use App\Domain\AlertStartedEvent;
use App\Port\EventListener\AlertCreatorListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class AlertCreatorListenerTest extends TestCase
{
    public function testOnAlertStartedEvent(): void
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

        $listener = new AlertCreatorListener($commandBus);

        $listener->onAlertStartedEvent(
            new AlertStartedEvent(
                '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                [1800, 2000, 2200],
            ),
        );

        self::assertCount(
            expectedCount: 1,
            haystack: $commandBus->commands,
        );

        $createAlertCommand = $commandBus->commands[0];

        self::assertInstanceOf(
            expected: CreateAlertCommand::class,
            actual: $createAlertCommand,
        );

        self::assertSame(
            expected: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            actual: $createAlertCommand->sensorId,
        );

        self::assertSame(
            expected: [1800, 2000, 2200],
            actual: $createAlertCommand->measurements,
        );
    }
}
