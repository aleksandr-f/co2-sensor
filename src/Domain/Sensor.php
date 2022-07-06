<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @final
 */
class Sensor implements DomainEventsProducerInterface
{
    use DomainEventsProducerTrait;

    private SensorStatusEnum $status = SensorStatusEnum::OK;

    public function __construct(
        private string $id,
    ) {
    }

    /** @param int[] $measurements */
    public function updateStatus(array $measurements): void
    {
        $oldStatus = $this->status;

        $measurementsMoreOrEqual2000 = 0;

        foreach ($measurements as $measurement) {
            if ($measurement >= 2000) {
                ++$measurementsMoreOrEqual2000;
            }
        }

        if ($measurementsMoreOrEqual2000 >= 3) {
            $this->alert(measurements: $measurements);

            return;
        }

        if ($measurementsMoreOrEqual2000 >= 1) {
            $this->warn();

            return;
        }

        $this->status = SensorStatusEnum::OK;

        if (SensorStatusEnum::ALERT === $oldStatus) {
            $this->addDomainEvent(
                event: new AlertResolvedEvent(
                    sensorId: $this->id,
                ),
            );
        }
    }

    /** @param int[] $measurements */
    private function alert(array $measurements): void
    {
        if (SensorStatusEnum::ALERT === $this->status) {
            return;
        }

        $this->status = SensorStatusEnum::ALERT;

        $this->addDomainEvent(event: new AlertStartedEvent(
            sensorId: $this->id,
            measurements: $measurements,
        ));
    }

    private function warn(): void
    {
        if (SensorStatusEnum::ALERT === $this->status) {
            return;
        }

        $this->status = SensorStatusEnum::WARN;
    }
}
