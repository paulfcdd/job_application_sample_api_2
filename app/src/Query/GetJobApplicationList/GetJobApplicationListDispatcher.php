<?php

declare(strict_types=1);

namespace App\Query\GetJobApplicationList;

use App\Repository\JobApplicationRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetJobApplicationListDispatcher
{
    public function __construct(
        private JobApplicationRepository $repository
    ) {
    }


    public function __invoke(GetJobApplicationListQuery $query): array
    {
        return $this->repository->getList($query->isRead, $query->page);
    }
}
