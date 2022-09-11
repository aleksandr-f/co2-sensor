<?php

declare(strict_types=1);

namespace App\Application;

interface AlertReadRepositoryInterface
{
    public function findByListAlertsQuery(ListAlertsQuery $query): array;
}
