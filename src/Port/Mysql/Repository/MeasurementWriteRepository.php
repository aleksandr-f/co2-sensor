<?php

declare(strict_types=1);

namespace App\Port\Mysql\Repository;

use App\Application\MeasurementWriteRepositoryInterface;
use DateTimeInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class MeasurementWriteRepository implements MeasurementWriteRepositoryInterface
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
}
