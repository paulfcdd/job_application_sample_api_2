<?php

declare(strict_types=1);

namespace App\Query\GetJobApplicationList;

use App\Query\QueryInterface;

class GetJobApplicationListQuery implements QueryInterface
{
    public function __construct(
        public bool   $isRead,
        public int    $page,
        public string $sort,
        public string $order,
    ) {
    }
}
