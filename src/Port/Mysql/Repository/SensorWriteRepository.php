<?php

declare(strict_types=1);

namespace App\Port\Mysql\Repository;

use App\Application\SensorWriteRepositoryInterface;
use App\Domain\Sensor;
use App\Port\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\EventDispatcher\EventDispatcherInterface;

final class SensorWriteRepository extends EntityRepository implements SensorWriteRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        EventDispatcherInterface $eventDispatcher,
    ) {
        parent::__construct(
            registry: $registry,
            entityClass: Sensor::class,
            eventDispatcher: $eventDispatcher,
        );
    }

    public function save(Sensor $sensor): void
    {
        $this->getEntityManager()->persist(entity: $sensor);

        $this->getEntityManager()->flush();

        $this->publishEvents();
    }
}
