<?php

declare(strict_types=1);

namespace App\Query\GetPositionByCode;

use App\Entity\Position;
use App\Exception\NotFoundException;
use App\Query\QueryDispatcherInterface;
use App\Service\PositionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetPositionByCodeDispatcher
{
    public function __construct(
        private PositionService $positionService
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function __invoke(GetPositionByCodeQuery $query): Position
    {
        return $this->positionService->getPositionBuyCode($query);
    }
}
