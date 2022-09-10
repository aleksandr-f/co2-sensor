<?php

declare(strict_types=1);

namespace App\Port;

use App\Port\Api\ListAlertsQuery;

interface AlertReadRepositoryInterface
{
    public function findByListAlertsQuery(ListAlertsQuery $query): array;
}
