<?php

declare(strict_types=1);

namespace Tests\Integration\Port\Mysql\Repository;

use App\Port\Mysql\Repository\MeasurementReadRepository;
use Tests\BaseKernelWithDBTestCase;
use Tests\Factories\MeasurementFactory;

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

    public function testGetMax(): void
    {
        $this->createTestData();

        self::assertSame(
            expected: 0,
            actual: $this->measurementRepository->getMax(
                sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
                days: 10,
            ),
        );

        self::assertSame(
            expected: 2000,
            actual: $this->measurementRepository->getMax(
                sensorId: '137e3b0b-2cc1-40b1-aa53-843b9d05775e',
                days: 10,
            ),
        );

        self::assertSame(
            expected: 1900,
            actual: $this->measurementRepository->getMax(
                sensorId: '137e3b0b-2cc1-40b1-aa53-843b9d05775e',
                days: 1,
            ),
        );
    }

    public function testGetAvg(): void
    {
        $this->createTestData();

        self::assertSame(
            expected: 0.0,
            actual: $this->measurementRepository->getAvg(
                sensorId: '3d08e9f0-9c00-4765-9645-b2a7201975e0',
                days: 10,
            ),
        );

        self::assertSame(
            expected: 1900.0,
            actual: $this->measurementRepository->getAvg(
                sensorId: '137e3b0b-2cc1-40b1-aa53-843b9d05775e',
                days: 10,
            ),
        );

        self::assertSame(
            expected: 1850.0,
            actual: $this->measurementRepository->getAvg(
                sensorId: '137e3b0b-2cc1-40b1-aa53-843b9d05775e',
                days: 1,
            ),
        );
    }

    private function createTestData(): void
    {
        /** @var MeasurementFactory $measurementFactory */
        $measurementFactory = self::getContainer()->get(id: MeasurementFactory::class);

        $measurementFactory->createOne(
            data: [
                'sensorId' => '137e3b0b-2cc1-40b1-aa53-843b9d05775e',
                'co2' => 1800,
            ],
        );

        $measurementFactory->createOne(
            data: [
                'sensorId' => '137e3b0b-2cc1-40b1-aa53-843b9d05775e',
                'co2' => 2000,
                'time' => (new \DateTime(datetime: '-5 days')),
            ],
        );

        $measurementFactory->createOne(
            data: [
                'sensorId' => '137e3b0b-2cc1-40b1-aa53-843b9d05775e',
                'co2' => 1900,
            ],
        );
    }
}
