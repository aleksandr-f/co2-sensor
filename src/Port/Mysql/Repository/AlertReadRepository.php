<?php

declare(strict_types=1);

namespace App\Port\Mysql\Repository;

use App\Application\AlertReadRepositoryInterface;
use App\Application\ListAlertsQuery;
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
                select: 'a.sensor_id, a.start_time, a.end_time, a.measurements',
            )
            ->from(from: 'alerts', alias: 'a')
        ;

        $queryBuilder->addOrderBy(sort: 'a.start_time', order: 'DESC');

        if ($query->sensorId) {
            $queryBuilder->andWhere(where: 'a.sensor_id = :sensorId');
            $queryBuilder->setParameter('sensorId', $query->sensorId);
        }

        if ($query->limit) {
            $queryBuilder->setMaxResults(maxResults: $query->limit);
        }

        if ($query->offset) {
            $queryBuilder->setFirstResult(firstResult: $query->offset);
        }

        $alerts = [];

        $i = 0;
        foreach ($queryBuilder->executeQuery()->fetchAllAssociative() as $alert) {
            $alerts[$i] = $alert;

            $alerts[$i]['measurements'] = \json_decode(
                json: $alert['measurements'],
                associative: true,
                flags: JSON_THROW_ON_ERROR,
            );

            ++$i;
        }

        return $alerts;
    }
}
