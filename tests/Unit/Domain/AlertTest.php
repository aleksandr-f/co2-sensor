<?php

namespace App\Tests\Unit\Domain;

use App\Domain\Alert;
use App\Domain\Measurement;
use PHPUnit\Framework\TestCase;

class AlertTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testResolve(): void
    {
        $alert = new Alert(
            sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
            measurements: [
                              new Measurement(
                                  sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
                                  co2: 2000,
                                  time: new \DateTime()
                              ),
                          ],
        );

        $alert->resolve();
    }
}
