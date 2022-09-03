<?php

declare(strict_types=1);

namespace App\Port\Mysql\Repository;

use App\Application\SensorNotFoundException;
use App\Application\SensorRepositoryInterface;
use App\Domain\Sensor;
use App\Port\EntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Psr\EventDispatcher\EventDispatcherInterface;

final class SensorRepository extends EntityRepository implements SensorRepositoryInterface
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

    /**
     * @throws Exception
     */
    public function exists(string $id): bool
    {
        $connection = $this->getEntityManager()->getConnection();

        $queryBuilder = $connection->createQueryBuilder();

        $queryBuilder
            ->select(select: '1')
            ->from(from: 'sensors', alias: 's')
            ->where('s.id = :id')
        ;

        $queryBuilder->setParameters(
            [
                'id' => $id,
            ],
        );

        return (bool) $queryBuilder->executeQuery()->fetchNumeric();
    }

    public function save(Sensor $sensor): void
    {
        $this->getEntityManager()->persist(entity: $sensor);

        $this->getEntityManager()->flush();

        $this->publishEvents();
    }

    public function get(string $id): Sensor
    {
        $sensor = $this->find(id: $id);

        if (!$sensor) {
            throw new SensorNotFoundException(message: 'Sensor was not found for id $id');
        }

        return $sensor;
    }
}
