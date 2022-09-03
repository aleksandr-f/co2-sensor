<?php

declare(strict_types=1);

namespace App\Port\Mysql\Repository;

use App\Application\MeasurementRepositoryInterface;
use DateTimeInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class MeasurementRepository implements MeasurementRepositoryInterface
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    /**
     * @throws Exception
     */
    public function create(
        string $sensorId,
        int $co2,
        DateTimeInterface $time,
    ): void {
        $this->connection->executeStatement(
            sql: '
                INSERT INTO measurements (sensor_id, co2, time)
                VALUES (:sensorId, :co2, :time)
            ',
            params: [
                'sensorId' => $sensorId,
                'co2' => $co2,
                'time' => $time->format(format: DateTimeInterface::ATOM),
            ],
        );
    }

    /**
     * @throws Exception
     */
    public function getLastMeasurements(
        string $sensorId,
        int $count,
    ): array {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select(select: '*')
            ->from(from: 'measurements', alias: 'm')
            ->where('m.sensor_id = :sensorId')
            ->orderBy(sort: 'm.time', order: 'DESC')
            ->setMaxResults(maxResults: $count)
        ;

        $queryBuilder->setParameters(
            [
                'sensorId' => $sensorId,
            ],
        );

        $statement = $queryBuilder->executeQuery();

        $result = $statement->fetchAllAssociative();

        return $result ?: [];
    }
}
