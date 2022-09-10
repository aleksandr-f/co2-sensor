<?php

declare(strict_types=1);

namespace App\Port\Api;

final class ListAlertsQuery
{
    public function __construct(
        public readonly string $sensorId,
        public readonly ?int $limit = null,
        public readonly ?int $offset = null,
    ) {
    }
}
