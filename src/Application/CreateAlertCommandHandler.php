<?php

declare(strict_types=1);

namespace App\Application;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateAlertCommandHandler
{
    public function __construct(
        private readonly AlertWriteRepositoryInterface $alertRepository,
    ) {
    }

    public function __invoke(CreateAlertCommand $command): void
    {
        $this->alertRepository->create(
            sensorId: $command->sensorId,
            measurements: $command->measurements,
        );
    }
}
