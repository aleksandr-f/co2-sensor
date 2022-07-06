<?php

namespace App\Tests\Unit\Domain;

use App\Domain\Alert;
use PHPUnit\Framework\TestCase;

final class AlertTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testResolve(): void
    {
        $alert = new Alert(
            sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
            measurements: [2000],
        );

        $alert->resolve();
    }
}
