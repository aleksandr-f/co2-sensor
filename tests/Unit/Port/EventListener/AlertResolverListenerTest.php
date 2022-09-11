<?php

declare(strict_types=1);

namespace Tests\Unit\Port\EventListener;

use App\Application\ResolveAlertCommand;
use App\Domain\AlertResolvedEvent;
use App\Port\EventListener\AlertResolverListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class AlertResolverListenerTest extends TestCase
{
    public function testOnAlertResolvedEvent(): void
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

        $listener = new AlertResolverListener($commandBus);

        $listener->onAlertResolvedEvent(
            new AlertResolvedEvent(
                sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            ),
        );

        self::assertCount(
            expectedCount: 1,
            haystack: $commandBus->commands,
        );

        $resolveAlertCommand = $commandBus->commands[0];

        self::assertInstanceOf(
            expected: ResolveAlertCommand::class,
            actual: $resolveAlertCommand,
        );

        self::assertSame(
            expected: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            actual: $resolveAlertCommand->sensorId,
        );
    }
}
