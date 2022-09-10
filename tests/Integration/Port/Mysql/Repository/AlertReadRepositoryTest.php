<?php

declare(strict_types=1);

namespace Tests\Integration\Port\Mysql\Repository;

use App\Port\Api\ListAlertsQuery;
use App\Port\Mysql\Repository\AlertReadRepository;
use Tests\BaseKernelWithDBTestCase;
use Tests\Factories\AlertFactory;

final class AlertReadRepositoryTest extends BaseKernelWithDBTestCase
{
    public function testFindByListAlertsQuery(): void
    {
        /** @var AlertFactory $alertFactory */
        $alertFactory = self::getContainer()->get(id: AlertFactory::class);

        $alertFactory->createOne(
            data: [
                '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            ],
        );

        /** @var AlertReadRepository $alertReadRepository */
        $alertReadRepository = self::getContainer()->get(id: AlertReadRepository::class);

        $alerts = $alertReadRepository->findByListAlertsQuery(
            query: new ListAlertsQuery(
                sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            ),
        );

        self::assertSame(
            expected: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            actual: $alerts[0]['sensor_id'],
        );

        $alerts = $alertReadRepository->findByListAlertsQuery(
            query: new ListAlertsQuery(
                sensorId: 'db10c7df-2def-48ab-853b-ce8eb15166af',
            ),
        );

        self::assertEmpty(actual: $alerts);
    }
}
