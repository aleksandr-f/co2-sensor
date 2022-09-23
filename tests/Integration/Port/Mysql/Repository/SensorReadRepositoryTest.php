<?php

declare(strict_types=1);

namespace Tests\Integration\Port\Mysql\Repository;

use App\Application\SensorNotFoundException;
use App\Port\Mysql\Repository\SensorReadRepository;
use Tests\BaseKernelWithDBTestCase;
use Tests\Factories\SensorFactory;

final class SensorReadRepositoryTest extends BaseKernelWithDBTestCase
{
    private SensorReadRepository $sensorRepository;

    private SensorFactory $sensorFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sensorRepository = self::getContainer()->get(id: SensorReadRepository::class);

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

    public function testGetNotFound(): void
    {
        $this->expectException(exception: SensorNotFoundException::class);

        $this->sensorRepository->get(id: '0c9cd912-dcb9-49bf-a260-662a6e090d93');
    }

    /** @doesNotPerformAssertions  */
    public function testGet(): void
    {
        $this->sensorFactory->createOne(data: [
            'id' => '0c9cd912-dcb9-49bf-a260-662a6e090d93',
        ]);

        $this->sensorRepository->get(id: '0c9cd912-dcb9-49bf-a260-662a6e090d93');
    }

    public function testGetAsArrayNotFound(): void
    {
        $this->expectException(exception: SensorNotFoundException::class);

        $this->sensorRepository->getAsArray(
            id: '0c9cd912-dcb9-49bf-a260-662a6e090d93',
        );

        $this->sensorFactory->createOne(data: [
            'id' => '0c9cd912-dcb9-49bf-a260-662a6e090d93',
        ]);

        self::assertNotEmpty(
            actual: $this->sensorRepository->getAsArray(
                id: '0c9cd912-dcb9-49bf-a260-662a6e090d93',
            ),
        );
    }

    public function testGetAsArray(): void
    {
        $this->sensorFactory->createOne(data: [
            'id' => '0c9cd912-dcb9-49bf-a260-662a6e090d93',
        ]);

        self::assertNotEmpty(
            actual: $this->sensorRepository->getAsArray(
                id: '0c9cd912-dcb9-49bf-a260-662a6e090d93',
            ),
        );
    }
}
