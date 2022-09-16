<?php

declare(strict_types=1);

namespace Tests\Application\API\Rest;

use Symfony\Component\HttpFoundation\Response;
use Tests\BaseRestApiTestCase;

final class CreateMeasurementControllerTest extends BaseRestApiTestCase
{
    public function testSuccessfulCreate(): void
    {
        $requestBody = [
            'co2' => 2000,
            'time' => '2019-02-01T18:55:47+00:00',
        ];

        $this->client->request(
            method: 'POST',
            uri: '/api/v1/sensors/137e3b0b-2cc1-40b1-aa53-843b9d05775e/measurements',
            parameters: [],
            files: [],
            server: self::REQUEST_DEFAULT_HEADERS,
            content: \json_encode(value: $requestBody),
        );

        static::assertEquals(
            expected: Response::HTTP_CREATED,
            actual: $this->client->getResponse()->getStatusCode(),
        );
    }
}
