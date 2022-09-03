<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Sensor;

interface SensorWriteRepositoryInterface
{
    public function save(Sensor $sensor): void;
}
