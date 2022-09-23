<?php

declare(strict_types=1);

namespace App\Port\Api\Rest;

final class GetSensorMetricsResponse
{
    public function __construct(
        public readonly int $maxLast30Days,
        public readonly float $avgLast30Days,
    ) {
    }
}
