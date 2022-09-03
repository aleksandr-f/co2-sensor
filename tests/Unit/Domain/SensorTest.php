<?php

namespace Tests\Unit\Domain;

use App\Domain\AlertResolvedEvent;
use App\Domain\AlertStartedEvent;
use App\Domain\Sensor;
use PHPUnit\Framework\TestCase;

final class SensorTest extends TestCase
{
    public function dataForTestUpdateStatusFrom(): array
    {
        return [
            'alert' => [
                'measurements' => [2000, 2000, 2000],
                'alertResolvedEventsCountExpected' => 0,
                'alertStartedEventsCountExpected' => 1,
                'initialMeasurements' => [],
            ],
            'warning' => [
                'measurements' => [2000, 1999, 2000],
                'alertResolvedEventsCountExpected' => 0,
                'alertStartedEventsCountExpected' => 0,
                'initialMeasurements' => [],
            ],
            'ok' => [
                'measurements' => [1500, 1700, 1300],
                'alertResolvedEventsCountExpected' => 0,
                'alertStartedEventsCountExpected' => 0,
                'initialMeasurements' => [],
            ],
            'no second alert' => [
                'measurements' => [2000, 2000, 2000],
                'alertResolvedEventsCountExpected' => 0,
                'alertStartedEventsCountExpected' => 0,
                'initialMeasurements' => [2000, 2000, 2000],
            ],
            'no warning if it is already alert' => [
                'measurements' => [2000, 2000, 1999],
                'alertResolvedEventsCountExpected' => 0,
                'alertStartedEventsCountExpected' => 0,
                'initialMeasurements' => [2000, 2000, 2000],
            ],
            'alert resolved' => [
                'measurements' => [1500, 1999, 1000],
                'alertResolvedEventsCountExpected' => 1,
                'alertStartedEventsCountExpected' => 0,
                'initialMeasurements' => [2000, 2000, 2000],
            ],
        ];
    }

    /**
     * @dataProvider dataForTestUpdateStatusFrom
     *
     * @param int[] $measurements
     * @param int[] $initialMeasurements
     */
    public function testUpdateStatusFromOk(
        array $measurements,
        int $alertResolvedEventsCountExpected,
        int $alertStartedEventsCountExpected,
        array $initialMeasurements,
    ): void {
        $sensor = new Sensor(id: '0d88d9a5-b2c5-471a-b517-dedbdc02c285');

        if ($initialMeasurements) {
            $sensor->updateStatus(measurements: $initialMeasurements);
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
            actual: $alertResolvedEventsCount,
        );

        self::assertSame(
            expected: $alertStartedEventsCountExpected,
            actual: $alertStartedEventsCount,
        );
    }
}
