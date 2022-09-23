<?php

declare(strict_types=1);

namespace App\Port\Api\Rest;

final class GetSensorResponse
{
    public function __construct(
        public readonly string $status,
    ) {
    }
}
