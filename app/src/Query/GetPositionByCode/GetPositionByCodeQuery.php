<?php

declare(strict_types=1);

namespace App\Query\GetPositionByCode;

use App\Query\QueryInterface;

class GetPositionByCodeQuery implements QueryInterface
{
    public function __construct(
        public string $code,
    ) {
    }
}
