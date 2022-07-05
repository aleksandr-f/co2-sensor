<?php

declare(strict_types=1);

namespace App\Domain;

final class AlertStartedEvent implements DomainEventInterface
{
    /**
     * @param Measurement[]
     */
    public function __construct(
        public readonly string $sensorId,
        public readonly array $measurements,
    ) {
    }
}
