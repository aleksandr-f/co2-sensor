<?php

declare(strict_types=1);

namespace App\Domain;

final class MeasurementCreatedEvent implements DomainEventInterface
{
    public function __construct(
        public readonly string $sensorId,
        public readonly int $co2,
        public readonly \DateTimeInterface $time,
    ) {
    }
}
