<?php

declare(strict_types=1);

namespace Tests\Integration\Port\Mysql\Repository;

use App\Domain\Sensor;
use App\Port\Mysql\Repository\SensorReadRepository;
use App\Port\Mysql\Repository\SensorWriteRepository;
use Tests\BaseKernelWithDBTestCase;
use Tests\Factories\SensorFactory;

final class SensorWriteRepositoryTest extends BaseKernelWithDBTestCase
{
    private SensorWriteRepository $sensorWriteRepository;

    private SensorReadRepository $sensorReadRepository;

    private SensorFactory $sensorFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sensorWriteRepository = self::getContainer()->get(id: SensorWriteRepository::class);

        $this->sensorReadRepository = self::getContainer()->get(id: SensorReadRepository::class);

        $this->sensorFactory = self::getContainer()->get(id: SensorFactory::class);
    }

    protected function tearDown(): void
    {
        unset($this->sensorWriteRepository, $this->sensorFactory);

        parent::tearDown();
    }

    /** @doesNotPerformAssertions */
    public function testSave(): void
    {
        $sensor = new Sensor(id: '0c9cd912-dcb9-49bf-a260-662a6e090d93');

        $this->sensorWriteRepository->save(sensor: $sensor);

        $this->sensorReadRepository->get(id: '0c9cd912-dcb9-49bf-a260-662a6e090d93');
    }
}
