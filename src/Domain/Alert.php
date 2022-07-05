<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @final
 */
class Alert
{
    /** @var int[] */
    private array $measurements;

    private ?\DateTimeInterface $endTime = null;

    /**
     * @param $measurements Measurement[]
     */
    public function __construct(
        private readonly string $sensorId,
        array $measurements,
        private readonly \DateTimeInterface $startTime = new \DateTime(),
    ) {
        foreach ($measurements as $measurement) {
            $this->measurements[] = $measurement->getCo2();
        }
    }

    public function resolve(): void
    {
        $this->endTime = new \DateTime();
    }
}
