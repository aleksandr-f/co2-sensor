<?php

declare(strict_types=1);

namespace App\Port\Api\Rest;

final class Alert
{
    /** @param int[] $measurements */
    public function __construct(
        public readonly \DateTimeInterface $startTime,
        public readonly ?\DateTimeInterface $endTime,
        public readonly array $measurements,
    ) {
    }
}
