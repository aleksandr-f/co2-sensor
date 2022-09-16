<?php

declare(strict_types=1);

namespace App\Port\Api\Rest;

final class CreateMeasurementRequest
{
    public function __construct(
        public readonly int $co2,
        public readonly \DateTimeInterface $time,
    ) {
    }
}
