<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Sensor;

interface SensorRepositoryInterface
{
    public function exists(string $id): bool;

    public function get(string $id): Sensor;

    public function save(Sensor $sensor): void;
}
