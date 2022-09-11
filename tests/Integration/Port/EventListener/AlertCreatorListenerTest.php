<?php

declare(strict_types=1);

namespace Tests\Integration\Port\EventListener;

use App\Application\CreateAlertCommand;
use App\Domain\AlertStartedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Tests\BaseKernelWithDBTestCase;
use Tests\Mocks\MessageBus;

final class AlertCreatorListenerTest extends BaseKernelWithDBTestCase
{
    public function testOnAlertStartedEvent(): void
    {
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = self::getContainer()->get(id: EventDispatcherInterface::class);

        /** @var MessageBus $messageBus */
        $messageBus = self::getContainer()->get(id: MessageBusInterface::class);

        $messageBus->messages = [];

        $eventDispatcher->dispatch(
            event: new AlertStartedEvent(
                sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                measurements: [1800, 2000, 2200],
            ),
        );

        $createAlertCommand = $messageBus->getFirstMessageByType(
            type: CreateAlertCommand::class,
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
