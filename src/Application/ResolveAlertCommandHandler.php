<?php

declare(strict_types=1);

namespace App\Application;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ResolveAlertCommandHandler
{
    public function __construct(
        private readonly AlertWriteRepositoryInterface $alertRepository,
    ) {
    }

    public function __invoke(ResolveAlertCommand $command): void
    {
        $this->alertRepository->updateEndTime(sensorId: $command->sensorId);
    }
}
