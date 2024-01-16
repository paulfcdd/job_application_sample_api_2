<?php

declare(strict_types=1);

namespace App\Query\GetPositionByCode;

use App\Entity\Position;
use App\Exception\NotFoundException;
use App\Query\QueryDispatcherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetPositionByCodeDispatcher implements QueryDispatcherInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function __invoke(GetPositionByCodeQuery $query): Position
    {
        $position = $this->entityManager->getRepository(Position::class)->findOneBy(['code' => $query->code]);
        if (!$position) {
            throw new NotFoundException('Position does not exists');
        }

        return $position;
    }
}
