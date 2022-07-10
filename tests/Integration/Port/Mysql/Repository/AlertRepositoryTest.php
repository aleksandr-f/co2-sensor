<?php

namespace App\Tests\Integration\Port\Mysql\Repository;

use App\Port\Mysql\Repository\AlertRepository;
use App\Tests\BaseKernelWithDBTestCase;
use Doctrine\DBAL\Connection;

final class AlertRepositoryTest extends BaseKernelWithDBTestCase
{
    private AlertRepository $alertRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->alertRepository = self::getContainer()->get(id: AlertRepository::class);
    }

    protected function tearDown(): void
    {
        unset($this->alertRepository);

        parent::tearDown();
    }

    public function testCreate(): void
    {
        $this->alertRepository->create(
            sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            measurements: [1080, 1050, 1000],
            startTime: new \DateTime(datetime: '2021-01-03T02:30:00+01:00'),
        );

        $alerts = $this->getAllAlerts(sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0');

        self::assertCount(expectedCount: 1, haystack: $alerts);

        self::assertSame(
            expected: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            actual: $alerts[0]['sensor_id'],
        );

        self::assertSame(
            expected: [1080, 1050, 1000],
            actual: json_decode(
                json: $alerts[0]['measurements'],
                associative: false,
                flags: JSON_THROW_ON_ERROR,
            ),
        );

        self::assertSame(
            expected: '2021-01-03 01:30:00',
            actual: $alerts[0]['start_time'],
        );
    }

    public function testCreateDefaultValueForStartDate(): void
    {
        $this->alertRepository->create(
            sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            measurements: [1080, 1050, 1000],
        );

        $alerts = $this->getAllAlerts(sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0');

        self::assertCount(expectedCount: 1, haystack: $alerts);

        self::assertSame(
            expected: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            actual: $alerts[0]['sensor_id'],
        );

        self::assertSame(
            expected: [1080, 1050, 1000],
            actual: json_decode(
                json: $alerts[0]['measurements'],
                associative: false,
                flags: JSON_THROW_ON_ERROR,
            ),
        );

        self::assertNotNull(
            actual: $alerts[0]['start_time'],
        );
    }

    public function testUpdateEndTime(): void
    {
        $this->alertRepository->create(
            sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            measurements: [1080, 1050, 1000],
        );

        $alerts = $this->getAllAlerts(sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0');

        self::assertNull(
            actual: $alerts[0]['end_time'],
        );

        $this->alertRepository->updateEndTime(
            sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            endTime: new \DateTime(datetime: '2021-01-03T02:30:00+01:00'),
        );

        $alerts = $this->getAllAlerts(sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0');

        self::assertSame(
            expected: '2021-01-03 01:30:00',
            actual: $alerts[0]['end_time'],
        );
    }

    public function testUpdateEndTimeWithDefaultValue(): void
    {
        $this->alertRepository->create(
            sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            measurements: [1080, 1050, 1000],
        );

        $alerts = $this->getAllAlerts(sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0');

        self::assertNull(
            actual: $alerts[0]['end_time'],
        );

        $this->alertRepository->updateEndTime(
            sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
        );

        $alerts = $this->getAllAlerts(sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0');

        self::assertNotNull(
            actual: $alerts[0]['end_time'],
        );
    }

    private function getAllAlerts(string $sensorId): array
    {
        $connection = self::getContainer()->get(id: Connection::class);

        $queryBuilder = $connection->createQueryBuilder();

        $queryBuilder
            ->select(select: '*')
            ->from(from: 'alerts', alias: 'a')
            ->where(predicates: 'a.sensor_id = :sensorId')
        ;

        $queryBuilder->setParameters(
            [
                'sensorId' => $sensorId,
            ],
        );

        $statement = $queryBuilder->executeQuery();

        $result = $statement->fetchAllAssociative();

        return $result ?: [];
    }
}
