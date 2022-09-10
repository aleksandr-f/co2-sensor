<?php

declare(strict_types=1);

namespace Tests\Integration\Application;

use App\Application\UpdateSensorCommand;
use App\Domain\AlertStartedEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Tests\BaseKernelWithDBTestCase;
use Tests\Factories\MeasurementFactory;
use Tests\Factories\SensorFactory;
use Tests\Mocks\EventDispatcher;

final class UpdateSensorCommandTest extends BaseKernelWithDBTestCase
{
    public function testUpdateWithAlert(): void
    {
        /** @var MeasurementFactory $measurementFactory */
        $measurementFactory = self::getContainer()->get(id: MeasurementFactory::class);
        $measurementFactory->createMany(
            count: 3,
            data: [
                'sensorId' => '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                'co2' => 2020,
            ],
        );

        /** @var SensorFactory $sensorFactory */
        $sensorFactory = self::getContainer()->get(id: SensorFactory::class);
        $sensorFactory->createOne([
            'id' => '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
        ]);

        /** @var MessageBusInterface $messageBus */
        $messageBus = self::getContainer()->get(id: MessageBusInterface::class);

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = self::getContainer()->get(id: EventDispatcher::class);

        self::assertEmpty($eventDispatcher->events);

        $messageBus->dispatch(
            new UpdateSensorCommand(sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6'),
        );

        /** @var AlertStartedEvent $event */
        $event = $eventDispatcher->getFirstEventByType(type: AlertStartedEvent::class);

        self::assertSame(
            '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
            $event->sensorId,
        );
    }

    public function testUpdateWithoutAlert(): void
    {
        /** @var MeasurementFactory $measurementFactory */
        $measurementFactory = self::getContainer()->get(id: MeasurementFactory::class);
        $measurementFactory->createMany(
            count: 3,
            data: [
                'sensorId' => '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
                'co2' => 1800,
            ],
        );

        /** @var SensorFactory $sensorFactory */
        $sensorFactory = self::getContainer()->get(id: SensorFactory::class);
        $sensorFactory->createOne([
            'id' => '5218eb04-9da6-4dd5-a780-cd50f9378ff6',
        ]);

        /** @var MessageBusInterface $messageBus */
        $messageBus = self::getContainer()->get(id: MessageBusInterface::class);

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = self::getContainer()->get(id: EventDispatcher::class);

        $messageBus->dispatch(
            new UpdateSensorCommand(sensorId: '5218eb04-9da6-4dd5-a780-cd50f9378ff6'),
        );

        self::assertEmpty($eventDispatcher->events);
    }
}
