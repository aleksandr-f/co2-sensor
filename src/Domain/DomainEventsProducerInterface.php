<?php

declare(strict_types=1);

namespace App\Domain;

interface DomainEventsProducerInterface
{
    public function getDomainEvents(): array;
}
