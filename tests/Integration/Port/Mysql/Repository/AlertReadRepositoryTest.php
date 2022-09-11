<?php

declare(strict_types=1);

namespace Tests\Integration\Port\Mysql\Repository;

use App\Application\ListAlertsQuery;
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

    public function testFindByListAlertsQueryLimit(): void
    {
        /** @var AlertFactory $alertFactory */
        $alertFactory = self::getContainer()->get(id: AlertFactory::class);

        $alertFactory->createMany(3);

        /** @var AlertReadRepository $alertReadRepository */
        $alertReadRepository = self::getContainer()->get(id: AlertReadRepository::class);

        $alerts = $alertReadRepository->findByListAlertsQuery(
            query: new ListAlertsQuery(),
        );

        self::assertCount(
            expectedCount: 3,
            haystack: $alerts,
        );

        $alerts = $alertReadRepository->findByListAlertsQuery(
            query: new ListAlertsQuery(limit: 1),
        );

        self::assertCount(
            expectedCount: 1,
            haystack: $alerts,
        );
    }

    public function testFindByListAlertsQueryOffset(): void
    {
        /** @var AlertFactory $alertFactory */
        $alertFactory = self::getContainer()->get(id: AlertFactory::class);

        $alertFactory->createMany(3);

        /** @var AlertReadRepository $alertReadRepository */
        $alertReadRepository = self::getContainer()->get(id: AlertReadRepository::class);

        $alerts = $alertReadRepository->findByListAlertsQuery(
            query: new ListAlertsQuery(),
        );

        self::assertCount(
            expectedCount: 3,
            haystack: $alerts,
        );

        $alerts = $alertReadRepository->findByListAlertsQuery(
            query: new ListAlertsQuery(offset: 1),
        );

        self::assertCount(
            expectedCount: 2,
            haystack: $alerts,
        );
    }
}
