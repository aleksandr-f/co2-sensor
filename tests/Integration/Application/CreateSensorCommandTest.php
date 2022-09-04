<?php

declare(strict_types=1);

namespace Tests\Integration\Application;

use App\Application\CreateSensorCommand;
use App\Application\SensorReadRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Tests\BaseKernelWithDBTestCase;

final class CreateSensorCommandTest extends BaseKernelWithDBTestCase
{
    /** @doesNotPerformAssertions  */
    public function testCommand(): void
    {
        /** @var MessageBusInterface $messageBus */
        $messageBus = self::getContainer()->get(id: MessageBusInterface::class);

        $messageBus->dispatch(new CreateSensorCommand(sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6'));

        /** @var SensorReadRepositoryInterface $sensorRepository */
        $sensorRepository = self::getContainer()->get(id: SensorReadRepositoryInterface::class);

        $sensorRepository->get(id: '5218eb04-9da6-4dd5-a780-cd50f9378ff6');
    }
}
