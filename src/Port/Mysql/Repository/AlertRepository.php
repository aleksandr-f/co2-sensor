<?php

declare(strict_types=1);

namespace App\Port\Mysql\Repository;

use App\Application\AlertRepositoryInterface;
use DateTimeInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Types;

final class AlertRepository implements AlertRepositoryInterface
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    /**
     * @param int[] $measurements
     *
     * @throws Exception
     */
    public function create(
        string $sensorId,
        array $measurements,
        DateTimeInterface $startTime = new \DateTime(),
    ): void {
        $this->connection->executeStatement(
            sql: '
                INSERT INTO alerts (sensor_id, measurements, start_time)
                VALUES (:sensorId, :measurements, :startTime)
            ',
            params: [
                'sensorId' => $sensorId,
                'measurements' => $measurements,
                'startTime' => $startTime->format(format: DateTimeInterface::ATOM),
            ],
            types: [
                'measurements' => Types::JSON,
            ],
        );
    }

    /**
     * @throws Exception
     */
    public function updateEndTime(
        string $sensorId,
        DateTimeInterface $endTime = new \DateTime(),
    ): void {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->update('alerts', 'a')
            ->set('a.end_time', ':endTime')
            ->where(predicates: 'a.sensor_id = :sensorId')
        ;

        $queryBuilder->setParameters(
            params: [
                'endTime' => $endTime->format(format: DateTimeInterface::ATOM),
                'sensorId' => $sensorId,
            ],
        );

        $queryBuilder->executeStatement();
    }
}
