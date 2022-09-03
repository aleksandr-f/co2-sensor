<?php

declare(strict_types=1);

namespace Tests\Integration\Port\Mysql\Repository;

use App\Port\Mysql\Repository\MeasurementReadRepository;
use Tests\BaseKernelWithDBTestCase;

final class MeasurementReadRepositoryTest extends BaseKernelWithDBTestCase
{
    private MeasurementReadRepository $measurementRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->measurementRepository = self::getContainer()->get(id: MeasurementReadRepository::class);
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
            count: 1,
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

        self::assertEmpty(actual: $measurements);

        $measurements = $this->measurementRepository->getLastMeasurements(
            sensorId: '8dc7646f-1ccc-4966-8af4-ade6dc81ef8a',
            count: 3,
        );

        self::assertEmpty(actual: $measurements);

        $this->measurementRepository->create(
            sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            co2: 1800,
            time: new \DateTime(datetime: '2021-01-03T02:30:00+01:00'),
        );

        $this->measurementRepository->create(
            sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            co2: 1800,
            time: new \DateTime(datetime: '2021-01-03T02:30:00+01:00'),
        );

        $measurements = $this->measurementRepository->getLastMeasurements(
            sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            count: 3,
        );

        self::assertCount(expectedCount: 3, haystack: $measurements);

        self::assertSame(
            expected: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            actual: $measurements[0]['sensor_id'],
        );
    }
}
