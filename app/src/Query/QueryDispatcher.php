<?php

declare(strict_types=1);

namespace App\Query;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryDispatcher implements QueryDispatcherInterface
{
    use HandleTrait;

    public function __construct(
        protected MessageBusInterface $bus,
    )
    {
        $this->messageBus = $this->bus;
    }

    public function dispatch(QueryInterface $query): mixed
    {
        return $this->handle($query);
    }
}
