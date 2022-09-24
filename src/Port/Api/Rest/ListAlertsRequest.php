<?php

declare(strict_types=1);

namespace App\Port\Api\Rest;

final class ListAlertsRequest
{
    public function __construct(
        public readonly int $limit = 10,
        public readonly int $offset = 0,
    ) {
        if ($limit > 100) {
            throw new \InvalidArgumentException(message: 'Limit is too high');
        }
    }
}
