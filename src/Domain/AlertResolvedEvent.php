<?php

declare(strict_types=1);

namespace App\Domain;

final class AlertResolvedEvent implements DomainEventInterface
{
    public function __construct(
        public readonly string $sensorId,
    ) {
    }
}
