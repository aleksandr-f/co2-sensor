<?php

declare(strict_types=1);

namespace App\Domain;

trait DomainEventsProducerTrait
{
    private array $domainEvents = [];

    public function getDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    private function addDomainEvent(DomainEventInterface $event): void
    {
        $this->domainEvents[] = $event;
    }
}
