<?php

declare(strict_types=1);

namespace App\Query\GetJobApplication;

use App\Query\QueryInterface;

class GetJobApplicationQuery implements QueryInterface
{
    public function __construct(
        public int $id
    ) {
    }
}
