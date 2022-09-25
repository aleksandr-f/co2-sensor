<?php

declare(strict_types=1);

namespace Tests\Application\API\Rest;

use Symfony\Component\HttpFoundation\Response;
use Tests\BaseRestApiTestCase;
use Tests\Factories\AlertFactory;

final class ListAlertsControllerTest extends BaseRestApiTestCase
{
    public function testListAlerts(): void
    {
        /** @var AlertFactory $alertFactory */
        $alertFactory = self::getContainer()->get(id: AlertFactory::class);

        $alertFactory->createOne(
            data: [
                'sensorId' => '137e3b0b-2cc1-40b1-aa53-843b9d05775e',
            ],
        );

        $alert = $alertFactory->createOne(
            data: [
                'sensorId' => '137e3b0b-2cc1-40b1-aa53-843b9d05775e',
            ],
        );

        $this->client->request(
            method: 'GET',
            uri: '/api/v1/sensors/137e3b0b-2cc1-40b1-aa53-843b9d05775e/alerts',
            parameters: [
                'limit' => 2,
                'offset' => 1,
            ],
        );

        self::assertEquals(
            expected: Response::HTTP_OK,
            actual: $this->client->getResponse()->getStatusCode(),
        );

        self::assertSame(
            expected: [
                'list' => [
                    [
                        'startTime' => (new \DateTime($alert['start_time']))->format(format: DATE_ATOM),
                        'endTime' => $alert['end_time'],
                        'measurements' => $alert['measurements'],
                    ],
                ],
            ],
            actual: \json_decode(
                json: $this->client->getResponse()->getContent(),
                associative: true,
                depth: JSON_THROW_ON_ERROR,
            ),
        );
    }

    public function testListAlertsLimitError(): void
    {
        $this->client->request(
            method: 'GET',
            uri: '/api/v1/sensors/137e3b0b-2cc1-40b1-aa53-843b9d05775e/alerts',
            parameters: [
                'limit' => 101,
                'offset' => 1,
            ],
        );

        self::assertEquals(
            expected: Response::HTTP_BAD_REQUEST,
            actual: $this->client->getResponse()->getStatusCode(),
        );
    }
}
