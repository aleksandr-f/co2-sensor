<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @final
 */
class Measurement implements DomainEventsProducerInterface
{
    use DomainEventsProducerTrait;

    public function __construct(
        private readonly string $sensorId,
        private readonly int $co2,
        private readonly \DateTimeInterface $time,
    ) {
        $this->addDomainEvent(event: new MeasurementCreatedEvent(
            sensorId: $this->sensorId,
            co2: $this->co2,
            time: $this->time,
        ));
    }

    public function getCo2(): int
    {
        return $this->co2;
    }
}
