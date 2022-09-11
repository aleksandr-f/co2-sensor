<?php

declare(strict_types=1);

namespace App\Port\EventListener;

use App\Application\CreateAlertCommand;
use App\Domain\AlertStartedEvent;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class AlertCreatorListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ) {
    }

    #[ArrayShape([AlertStartedEvent::class => 'string'])]
    public static function getSubscribedEvents(): array
    {
        return [
            AlertStartedEvent::class => 'onAlertStartedEvent',
        ];
    }

    public function onAlertStartedEvent(
        AlertStartedEvent $event,
    ): void {
        $this->commandBus->dispatch(new CreateAlertCommand(
            sensorId: $event->sensorId,
            measurements: $event->measurements,
        ));
    }
}
