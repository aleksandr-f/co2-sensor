<?php

declare(strict_types=1);

namespace Tests\Application\API\Rest;

use Symfony\Component\HttpFoundation\Response;
use Tests\BaseRestApiTestCase;
use Tests\Factories\SensorFactory;

final class GetSensorControllerTest extends BaseRestApiTestCase
{
    public function testNotFound(): void
    {
        $this->client->request(
            method: 'GET',
            uri: '/api/v1/sensors/137e3b0b-2cc1-40b1-aa53-843b9d05775e',
        );

        self::assertEquals(
            expected: Response::HTTP_NOT_FOUND,
            actual: $this->client->getResponse()->getStatusCode(),
        );
    }

    public function testFound(): void
    {
        /** @var SensorFactory $sensorFactory */
        $sensorFactory = self::getContainer()->get(id: SensorFactory::class);

        $sensorFactory->createOne(data: [
            'id' => '137e3b0b-2cc1-40b1-aa53-843b9d05775e',
        ]);

        $this->client->request(
            method: 'GET',
            uri: '/api/v1/sensors/137e3b0b-2cc1-40b1-aa53-843b9d05775e',
        );

        self::assertEquals(
            expected: Response::HTTP_OK,
            actual: $this->client->getResponse()->getStatusCode(),
        );

        self::assertSame(
            expected: [
                'status' => 'OK',
            ],
            actual: \json_decode(
                json: $this->client->getResponse()->getContent(),
                associative: true,
                depth: JSON_THROW_ON_ERROR,
            ),
        );
    }
}
