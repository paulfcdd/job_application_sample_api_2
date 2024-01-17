<?php

declare(strict_types=1);

namespace App\Query\GetJobApplicationList;

use App\Repository\JobApplicationRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetJobApplicationListDispatcher
{
    public function __construct(
        private JobApplicationRepository $repository,
        private ParameterBagInterface $parameterBag
    ) {
    }

    public function __invoke(GetJobApplicationListQuery $query): array
    {
        $limit = $this->parameterBag->get('records_per_page');
        $list = $this->repository->getList($query->isRead, $limit, $query->sort, $query->order, $query->page, $query->position);
        $total = $this->repository->getTotal($query->isRead, $query->position);
        $pages = (int)ceil($total / $limit);

        return [
            'pages' => $pages,
            'total' => $total,
            'list' => $list,
        ];
    }
}
