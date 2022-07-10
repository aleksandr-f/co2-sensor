<?php

declare(strict_types=1);

namespace App\Port;

use App\Domain\DomainEventsProducerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\EventDispatcher\EventDispatcherInterface;

abstract class EntityRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        string $entityClass,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
        parent::__construct(registry: $registry, entityClass: $entityClass);
    }

    final protected function publishEvents(): void
    {
        $domainEvents = [];

        foreach ($this->getEntityManager()->getUnitOfWork()->getIdentityMap() as $entities) {
            foreach ($entities as $entity) {
                if ($entity instanceof DomainEventsProducerInterface) {
                    $domainEvents = [...$domainEvents, ...$entity->getDomainEvents()];
                }
            }
        }

        foreach ($domainEvents as $event) {
            $this->eventDispatcher->dispatch(event: $event);
        }
    }
}
