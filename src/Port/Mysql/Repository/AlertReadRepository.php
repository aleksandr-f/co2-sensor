<?php

declare(strict_types=1);

namespace App\Port\Mysql\Repository;

use App\Port\AlertReadRepositoryInterface;
use App\Port\Api\ListAlertsQuery;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class AlertReadRepository implements AlertReadRepositoryInterface
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    /**
     * @throws Exception
     */
    public function findByListAlertsQuery(ListAlertsQuery $query): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select(
                select: 'sensor_id, start_time, end_time, measurements',
            )
            ->from(from: 'alerts', alias: 'm')
            ->where('m.sensor_id = :sensorId')
        ;

        $queryBuilder->setParameters(
            [
                'sensorId' => $query->sensorId,
            ],
        );

        if ($query->limit) {
            $queryBuilder->setMaxResults(maxResults: $query->limit);
        }

        if ($query->offset) {
            $queryBuilder->setFirstResult(firstResult: $query->offset);
        }

        return $queryBuilder->executeQuery()->fetchAllAssociative();
    }
}
