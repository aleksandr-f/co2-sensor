<?php

declare(strict_types=1);

namespace Tests\Integration\Port\Mysql\Repository;

use App\Port\Mysql\Repository\MeasurementReadRepository;
use App\Port\Mysql\Repository\MeasurementWriteRepository;
use Tests\BaseKernelWithDBTestCase;

final class MeasurementWriteRepositoryTest extends BaseKernelWithDBTestCase
{
    private MeasurementWriteRepository $measurementWriteRepository;

    private MeasurementReadRepository $measurementReadRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->measurementWriteRepository = self::getContainer()->get(id: MeasurementWriteRepository::class);

        $this->measurementReadRepository = self::getContainer()->get(id: MeasurementReadRepository::class);
    }

    protected function tearDown(): void
    {
        unset(
            $this->measurementReadRepository,
            $this->measurementWriteRepository
        );

        parent::tearDown();
    }

    public function testCreate(): void
    {
        $this->measurementWriteRepository->create(
            sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
            co2: 1800,
            time: new \DateTime(datetime: '2021-01-03T02:30:00+01:00'),
        );

        $measurements = $this->measurementReadRepository->getLastMeasurements(
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
}
