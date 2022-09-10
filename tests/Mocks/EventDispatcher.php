<?php

declare(strict_types=1);

namespace Tests\Mocks;

use JetBrains\PhpStorm\Pure;
use Psr\EventDispatcher\EventDispatcherInterface;

final class EventDispatcher implements EventDispatcherInterface
{
    public array $events = [];

    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function dispatch(object $event): object
    {
        $this->events[] = $event;

        return $this->eventDispatcher->dispatch(event: $event);
    }

    #[Pure]
    public function getFirstEventByType(string $type): ?object
    {
        foreach ($this->events as $event) {
            if ($event instanceof $type) {
                return $event;
            }
        }

        return null;
    }
}
