<?php

declare(strict_types=1);

namespace Tests\Mocks;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessageBus implements MessageBusInterface
{
    public array $messages = [];

    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function dispatch(
        object $message,
        array $stamps = [],
    ): Envelope {
        $this->messages[] = $message;

        return $this->messageBus->dispatch(
            $message,
            $stamps,
        );
    }

    #[Pure]
    public function getFirstMessageByType(string $type): ?object
    {
        foreach ($this->messages as $message) {
            if ($message instanceof $type) {
                return $message;
            }
        }

        return null;
    }
}
