<?php

declare(strict_types=1);

namespace App\Query\GetJobApplicationList;

use App\Service\JobApplication\JobApplicationService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetJobApplicationListDispatcher
{
    public function __construct(
        private JobApplicationService $jobApplicationService,
    ) {
    }

    public function __invoke(GetJobApplicationListQuery $query): array
    {
        return $this->jobApplicationService->getJobApplicationList($query);
    }
}
