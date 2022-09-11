<?php

declare(strict_types=1);

namespace Tests\Integration\Application;

use App\Application\AlertReadRepositoryInterface;
use App\Application\ListAlertsQuery;
use App\Application\ResolveAlertCommand;
use Symfony\Component\Messenger\MessageBusInterface;
use Tests\BaseKernelWithDBTestCase;
use Tests\Factories\AlertFactory;

final class ResolveAlertCommandTest extends BaseKernelWithDBTestCase
{
    public function testCommand(): void
    {
        /** @var AlertFactory $alertFactory */
        $alertFactory = self::getContainer()->get(id: AlertFactory::class);

        $alert1 = $alertFactory->createOne(
            data: [
                '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            ],
        );

        $alert2 = $alertFactory->createOne(
            data: [
                '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            ],
        );

        self::assertNull($alert1['end_time']);
        self::assertNull($alert2['end_time']);

        /** @var MessageBusInterface $messageBus */
        $messageBus = self::getContainer()->get(id: MessageBusInterface::class);

        $messageBus->dispatch(new ResolveAlertCommand(sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6'));

        /** @var \App\Application\AlertReadRepositoryInterface $alertReadRepository */
        $alertReadRepository = self::getContainer()->get(id: AlertReadRepositoryInterface::class);

        $alerts = $alertReadRepository->findByListAlertsQuery(
            query: new ListAlertsQuery(
                sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            ),
        );

        self::assertNotNull($alerts[0]['end_time']);
        self::assertNotNull($alerts[1]['end_time']);
    }
}
