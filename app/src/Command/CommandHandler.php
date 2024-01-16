<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Messenger\MessageBusInterface;

readonly class CommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
    )
    {
    }

    public function handle(CommandInterface $command): void
    {
        $this->messageBus->dispatch($command);
    }
}
