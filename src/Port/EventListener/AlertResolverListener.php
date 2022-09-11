<?php

declare(strict_types=1);

namespace App\Port\EventListener;

use App\Application\ResolveAlertCommand;
use App\Domain\AlertResolvedEvent;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class AlertResolverListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ) {
    }

    #[ArrayShape([AlertResolvedEvent::class => 'string'])]
    public static function getSubscribedEvents(): array
    {
        return [
            AlertResolvedEvent::class => 'onAlertResolvedEvent',
        ];
    }

    public function onAlertResolvedEvent(
        AlertResolvedEvent $event,
    ): void {
        $this->commandBus->dispatch(new ResolveAlertCommand(
            sensorId: $event->sensorId,
        ));
    }
}
