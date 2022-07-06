<?php

declare(strict_types=1);

namespace App\Domain;

interface DomainEventsProducerInterface
{
    /** @return DomainEventInterface[] */
    public function getDomainEvents(): array;
}
