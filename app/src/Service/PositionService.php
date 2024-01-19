<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Position;
use App\Exception\NotFoundException;
use App\Query\GetPositionByCode\GetPositionByCodeQuery;
use App\Repository\PositionRepository;

readonly class PositionService
{
    public function __construct(
        private PositionRepository $repository
    ) {
    }

    public function getPositionBuyCode(GetPositionByCodeQuery $query): Position
    {
        $position = $this->repository->findOneBy(['code' => $query->code]);
        if (!$position) {
            throw new NotFoundException('Position does not exists');
        }

        return $position;
    }
}
