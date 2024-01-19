<?php

declare(strict_types=1);

namespace App\Query\GetFileByName;

use App\Query\QueryInterface;

readonly class GetFileByNameQuery implements QueryInterface
{
    public function __construct(
        public string $fileName
    )
    {

    }
}
