<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Application\AlertWriteRepositoryInterface;
use App\Port\AlertReadRepositoryInterface;
use App\Port\Api\ListAlertsQuery;

final class AlertFactory
{
    public function __construct(
        private readonly AlertWriteRepositoryInterface $alertWriteRepository,
        private readonly AlertReadRepositoryInterface $alertReadRepository,
    ) {
    }

    public function createOne(
        array $data = [],
    ): array {
        $sensorId = $data['sensorId'] ?? '5218eb04-9da6-4dd5-a780-cd50f9378ff6';

        $this->alertWriteRepository->create(
            sensorId: $sensorId,
            measurements: $data['measurements'] ?? [1800, 1800, 1800],
        );

        $alerts = $this->alertReadRepository->findByListAlertsQuery(
            query: new ListAlertsQuery(
                sensorId: $sensorId,
                limit: 1,
            ),
        );

        return $alerts[0];
    }
}
