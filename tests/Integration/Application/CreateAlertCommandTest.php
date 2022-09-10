<?php

declare(strict_types=1);

namespace Tests\Integration\Application;

use App\Application\CreateAlertCommand;
use App\Port\Api\ListAlertsQuery;
use App\Port\Mysql\Repository\AlertReadRepository;
use Symfony\Component\Messenger\MessageBusInterface;
use Tests\BaseKernelWithDBTestCase;

final class CreateAlertCommandTest extends BaseKernelWithDBTestCase
{
    public function testCommand(): void
    {
        /** @var MessageBusInterface $messageBus */
        $messageBus = self::getContainer()->get(id: MessageBusInterface::class);

        $messageBus->dispatch(
            new CreateAlertCommand(
                sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                measurements: [1800, 1800, 1800],
            ),
        );

        /** @var AlertReadRepository $alertReadRepository */
        $alertReadRepository = self::getContainer()->get(id: AlertReadRepository::class);

        $alerts = $alertReadRepository->findByListAlertsQuery(
            query: new ListAlertsQuery(
                sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            ),
        );

        self::assertSame(
            expected: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            actual: $alerts[0]['sensor_id'],
        );

        self::assertSame(
            expected: [1800, 1800, 1800],
            actual: \json_decode(
                json: $alerts[0]['measurements'],
            ),
        );
    }
}
