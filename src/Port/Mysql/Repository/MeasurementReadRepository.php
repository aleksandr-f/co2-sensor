<?php

declare(strict_types=1);

namespace App\Port\Mysql\Repository;

use App\Application\MeasurementReadRepositoryInterface;
use DateTimeInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class MeasurementReadRepository implements MeasurementReadRepositoryInterface
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
            ->select(select: 'm.sensor_id, m.co2, m.time')
            ->from(from: 'measurements', alias: 'm')
            ->where(predicates: 'm.sensor_id = :sensorId')
            ->orderBy(sort: 'm.time', order: 'DESC')
            ->setMaxResults(maxResults: $count)
        ;

        $queryBuilder->setParameters(
            params: [
                'sensorId' => $sensorId,
            ],
        );

        $statement = $queryBuilder->executeQuery();

        $result = $statement->fetchAllAssociative();

        if ($count !== count($result)) {
            return [];
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function getMax(string $sensorId, int $days): int
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $time = (new \DateTime(datetime: "-$days days"))->format(format: 'Y-m-d H:i:s');

        $queryBuilder
            ->select(select: 'MAX(m.co2)')
            ->from(from: 'measurements', alias: 'm')
            ->where('m.sensor_id = :sensorId', 'm.time >= :time')
            ->groupBy(groupBy: 'm.sensor_id')
        ;

        $queryBuilder->setParameters(
            params: [
                'sensorId' => $sensorId,
                'time' => $time,
            ],
        );

        $result = $queryBuilder->executeQuery()->fetchNumeric();

        return $result ? $result[0] : 0;
    }

    /**
     * @throws Exception
     */
    public function getAvg(string $sensorId, int $days): float
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $time = (new \DateTime(datetime: "-$days days"))->format(format: 'Y-m-d H:i:s');

        $queryBuilder
            ->select(select: 'AVG(m.co2)')
            ->from(from: 'measurements', alias: 'm')
            ->where('m.sensor_id = :sensorId', 'm.time >= :time')
            ->groupBy(groupBy: 'm.sensor_id')
        ;

        $queryBuilder->setParameters(
            params: [
                'sensorId' => $sensorId,
                'time' => $time,
            ],
        );

        $result = $queryBuilder->executeQuery()->fetchNumeric();

        return $result ? (float) $result[0] : 0;
    }
}
