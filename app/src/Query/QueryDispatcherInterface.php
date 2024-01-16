<?php

declare(strict_types=1);

namespace App\Query;

interface QueryDispatcherInterface
{
    public function dispatch(QueryInterface $query): mixed;
}