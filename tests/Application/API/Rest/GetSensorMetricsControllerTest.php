<?php

declare(strict_types=1);

namespace Tests\Application\API\Rest;

use Symfony\Component\HttpFoundation\Response;
use Tests\BaseRestApiTestCase;
use Tests\Factories\MeasurementFactory;

final class GetSensorMetricsControllerTest extends BaseRestApiTestCase
{
    public function testGetSensorMetrics(): void
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
            ],
        );

        $measurementFactory->createOne(
            data: [
                'sensorId' => '137e3b0b-2cc1-40b1-aa53-843b9d05775e',
                'co2' => 1900,
            ],
        );

        $this->client->request(
            method: 'GET',
            uri: '/api/v1/sensors/137e3b0b-2cc1-40b1-aa53-843b9d05775e/metrics',
        );

        self::assertEquals(
            expected: Response::HTTP_OK,
            actual: $this->client->getResponse()->getStatusCode(),
        );

        self::assertSame(
            expected: [
                'maxLast30Days' => 2000,
                'avgLast30Days' => 1900,
            ],
            actual: \json_decode(
                json: $this->client->getResponse()->getContent(),
                associative: true,
                depth: JSON_THROW_ON_ERROR,
            ),
        );
    }
}
