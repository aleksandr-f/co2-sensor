<?php

namespace App\Tests\Unit\Domain;

use App\Domain\AlertResolvedEvent;
use App\Domain\AlertStartedEvent;
use App\Domain\Measurement;
use App\Domain\Sensor;
use PHPUnit\Framework\TestCase;

class SensorTest extends TestCase
{
    public function dataForTestUpdateStatusFrom(): array
    {
        return [
//            'alert' => [
//                'measurements' => [
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 2000,
//                        time: new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 2000,
//                        time: new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 2000,
//                        time: new \DateTime()
//                    ),
//                ],
//                'alertResolvedEventsCountExpected' => 0,
//                'alertStartedEventsCountExpected' => 1,
//                'updateWithMeasurements' => [],
//            ],
//            'warning' => [
//                'measurements' => [
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 2000,
//                        time: new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 1999,
//                        time: new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 2000,
//                        time: new \DateTime()
//                    ),
//                ],
//                'alertResolvedEventsCountExpected' => 0,
//                'alertStartedEventsCountExpected' => 0,
//                'updateWithMeasurements' => [],
//            ],
            'ok' => [
                'measurements' => [
                    new Measurement(
                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
                        co2: 1500,
                        time: new \DateTime()
                    ),
                    new Measurement(
                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
                        co2: 1700,
                        time: new \DateTime()
                    ),
                    new Measurement(
                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
                        co2: 1300,
                        time: new \DateTime()
                    ),
                ],
                'alertResolvedEventsCountExpected' => 0,
                'alertStartedEventsCountExpected' => 0,
                'updateWithMeasurements' => [],
            ],
//            'no second alert' => [
//                'measurements' => [
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 2000,
//                        time: new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 2000,
//                        time: new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 2000,
//                        time: new \DateTime()
//                    ),
//                ],
//                'alertResolvedEventsCountExpected' => 0,
//                'alertStartedEventsCountExpected' => 0,
//                'updateWithMeasurements' => [
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2:      2000,
//                        time:     new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2:      2000,
//                        time:     new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2:      2000,
//                        time:     new \DateTime()
//                    ),
//                ],
//            ],
//            'no warning if it is already alert' => [
//                'measurements' => [
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 2000,
//                        time: new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 1999,
//                        time: new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 2000,
//                        time: new \DateTime()
//                    ),
//                ],
//                'alertResolvedEventsCountExpected' => 0,
//                'alertStartedEventsCountExpected' => 0,
//                'updateWithMeasurements' => [
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2:      2000,
//                        time:     new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2:      2000,
//                        time:     new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2:      2000,
//                        time:     new \DateTime()
//                    ),
//                ],
//            ],
//            'alert resolved' => [
//                'measurements' => [
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 1500,
//                        time: new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 1999,
//                        time: new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2: 1000,
//                        time: new \DateTime()
//                    ),
//                ],
//                'alertResolvedEventsCountExpected' => 1,
//                'alertStartedEventsCountExpected' => 0,
//                'updateWithMeasurements' => [
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2:      2000,
//                        time:     new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2:      2000,
//                        time:     new \DateTime()
//                    ),
//                    new Measurement(
//                        sensorId: '925daaf9-dc32-4250-b78c-336c4b51b9e6',
//                        co2:      2000,
//                        time:     new \DateTime()
//                    ),
//                ],
//            ]
        ];
    }

    /**
     * @dataProvider dataForTestUpdateStatusFrom
     *
     * @param Measurement[] $measurements
     * @param Measurement[] $updateWithMeasurements
     */
    public function testUpdateStatusFromOk(
        array $measurements,
        int $alertResolvedEventsCountExpected,
        int $alertStartedEventsCountExpected,
        array $updateWithMeasurements
    ): void {
        $sensor = new Sensor(id: '0d88d9a5-b2c5-471a-b517-dedbdc02c285');

        if ($updateWithMeasurements) {
            $sensor->updateStatus(measurements: $updateWithMeasurements);
            $sensor->getDomainEvents();
        }

        $sensor->updateStatus(measurements: $measurements);

        $alertResolvedEventsCount = 0;
        $alertStartedEventsCount = 0;

        foreach ($sensor->getDomainEvents() as $event) {
            if ($event instanceof AlertResolvedEvent) {
                ++$alertResolvedEventsCount;
            }
            if ($event instanceof AlertStartedEvent) {
                ++$alertStartedEventsCount;
            }
        }

        self::assertSame(
            expected: $alertResolvedEventsCountExpected,
            actual: $alertResolvedEventsCount
        );

        self::assertSame(
            expected: $alertStartedEventsCountExpected,
            actual: $alertStartedEventsCount
        );
    }
}
