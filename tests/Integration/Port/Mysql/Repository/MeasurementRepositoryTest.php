<?php

namespace App\Tests\Integration\Port\Mysql\Repository;

use App\Application\MeasurementRepositoryInterface;
use App\Port\Mysql\Repository\MeasurementRepository;
use App\Tests\BaseKernelWithDBTestCase;

final class MeasurementRepositoryTest extends BaseKernelWithDBTestCase
{
    private MeasurementRepository $measurementRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->measurementRepository = self::getContainer()->get(id: MeasurementRepositoryInterface::class);
    }

    protected function tearDown(): void
    {
        unset($this->measurementRepository);
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
}
