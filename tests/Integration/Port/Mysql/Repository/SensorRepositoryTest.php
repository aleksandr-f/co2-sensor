<?php

declare(strict_types=1);

namespace Tests\Integration\Port\Mysql\Repository;

use App\Application\SensorNotFoundException;
use App\Domain\Sensor;
use App\Port\Mysql\Repository\SensorRepository;
use Tests\BaseKernelWithDBTestCase;
use Tests\Factories\SensorFactory;

final class SensorRepositoryTest extends BaseKernelWithDBTestCase
{
    private SensorRepository $sensorRepository;

    private SensorFactory $sensorFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sensorRepository = self::getContainer()->get(id: SensorRepository::class);

        $this->sensorFactory = self::getContainer()->get(id: SensorFactory::class);
    }

    protected function tearDown(): void
    {
        unset($this->sensorRepository, $this->sensorFactory);

        parent::tearDown();
    }

    public function testExists(): void
    {
        self::assertFalse(
            condition: $this->sensorRepository->exists(
                id: '0c9cd912-dcb9-49bf-a260-662a6e090d93',
            ),
        );

        $this->sensorFactory->createOne(data: [
            'id' => '0c9cd912-dcb9-49bf-a260-662a6e090d93',
        ]);

        self::assertTrue(
            condition: $this->sensorRepository->exists(
                id: '0c9cd912-dcb9-49bf-a260-662a6e090d93',
            ),
        );
    }

    public function testGet(): void
    {
        $this->expectException(exception: SensorNotFoundException::class);

        $this->sensorRepository->get(id: '0c9cd912-dcb9-49bf-a260-662a6e090d93');

        $this->sensorFactory->createOne(data: [
            'id' => '0c9cd912-dcb9-49bf-a260-662a6e090d93',
        ]);

        $this->sensorRepository->get(id: '0c9cd912-dcb9-49bf-a260-662a6e090d93');
    }

    /** @doesNotPerformAssertions */
    public function testSave(): void
    {
        $sensor = new Sensor(id: '0c9cd912-dcb9-49bf-a260-662a6e090d93');

        $this->sensorRepository->save(sensor: $sensor);

        $this->sensorRepository->get(id: '0c9cd912-dcb9-49bf-a260-662a6e090d93');
    }
}
