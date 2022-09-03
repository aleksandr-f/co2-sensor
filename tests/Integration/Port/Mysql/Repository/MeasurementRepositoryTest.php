<?php

declare(strict_types=1);

namespace Tests\Integration\Port\Mysql\Repository;

use App\Port\Mysql\Repository\MeasurementRepository;
use Tests\BaseKernelWithDBTestCase;

final class MeasurementRepositoryTest extends BaseKernelWithDBTestCase
{
    private MeasurementRepository $measurementRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->measurementRepository = self::getContainer()->get(id: MeasurementRepository::class);
    }

    protected function tearDown(): void
    {
        unset($this->measurementRepository);

        parent::tearDown();
    }

    public function testCreate(): void
    {
        $this->measurementRepository->create(
            sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            co2: 1800,
            time: new \DateTime(datetime: '2021-01-03T02:30:00+01:00'),
        );

        $measurements = $this->measurementRepository->getLastMeasurements(
            sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            count: 3,
        );

        self::assertCount(expectedCount: 1, haystack: $measurements);

        self::assertSame(
            expected: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            actual: $measurements[0]['sensor_id'],
        );

        self::assertSame(
            expected: 1800,
            actual: $measurements[0]['co2'],
        );

        self::assertSame(
            expected: '2021-01-03 01:30:00',
            actual: $measurements[0]['time'],
        );
    }

    public function testGetLastMeasurements(): void
    {
        $this->measurementRepository->create(
            sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            co2: 1800,
            time: new \DateTime(datetime: '2021-01-03T02:30:00+01:00'),
        );

        $measurements = $this->measurementRepository->getLastMeasurements(
            sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            count: 3,
        );

        self::assertCount(expectedCount: 1, haystack: $measurements);

        self::assertSame(
            expected: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            actual: $measurements[0]['sensor_id'],
        );

        $measurements = $this->measurementRepository->getLastMeasurements(
            sensorId: '8dc7646f-1ccc-4966-8af4-ade6dc81ef8a',
            count: 3,
        );

        self::assertEmpty(actual: $measurements);
    }

    private function getAllMeasurements(string $sensorId): array
    {
        $connection = self::getContainer()->get(id: Connection::class);

        $queryBuilder = $connection->createQueryBuilder();

        $queryBuilder
            ->select(select: '*')
            ->from(from: 'measurements', alias: 'm')
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
