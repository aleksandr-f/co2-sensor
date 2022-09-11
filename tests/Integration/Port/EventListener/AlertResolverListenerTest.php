<?php

declare(strict_types=1);

namespace Tests\Integration\Port\EventListener;

use App\Application\ResolveAlertCommand;
use App\Domain\AlertResolvedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Tests\BaseKernelWithDBTestCase;
use Tests\Mocks\MessageBus;

final class AlertResolverListenerTest extends BaseKernelWithDBTestCase
{
    public function testOnAlertStartedEvent(): void
    {
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = self::getContainer()->get(id: EventDispatcherInterface::class);

        /** @var MessageBus $messageBus */
        $messageBus = self::getContainer()->get(id: MessageBusInterface::class);

        $messageBus->messages = [];

        $eventDispatcher->dispatch(
            event: new AlertResolvedEvent(
                sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            ),
        );

        $resolveAlertCommand = $messageBus->getFirstMessageByType(
            type: ResolveAlertCommand::class,
        );

        self::assertSame(
            expected: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            actual: $resolveAlertCommand->sensorId,
        );
    }
}
