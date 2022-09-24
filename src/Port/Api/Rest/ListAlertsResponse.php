<?php

declare(strict_types=1);

namespace App\Port\Api\Rest;

final class ListAlertsResponse
{
    /** @param Alert[] $list */
    public function __construct(
        public readonly array $list,
    ) {
    }

    /**
     * @throws \Exception
     */
    public static function createFromArray(array $alerts): self
    {
        $list = [];

        foreach ($alerts as $alert) {
            $list[] = new Alert(
                startTime: new \DateTimeImmutable(datetime: $alert['start_time']),
                endTime: $alert['end_time'] ? new \DateTimeImmutable(datetime: $alert['end_time']) : null,
                measurements: $alert['measurements'],
            );
        }

        return new self(list: $list);
    }
}
