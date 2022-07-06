<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @final
 */
class Alert
{
    private ?\DateTimeInterface $endTime = null;

    /** @param int[] $measurements */
    public function __construct(
        private readonly string $sensorId,
        private readonly array $measurements,
        private readonly \DateTimeInterface $startTime = new \DateTime(),
    ) {
    }

    public function resolve(): void
    {
        $this->endTime = new \DateTime();
    }
}
