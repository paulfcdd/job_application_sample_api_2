<?php

declare(strict_types=1);

namespace App\Query\GetJobApplication;

use App\Controller\JobApplication\Retrieve\JobApplicationRetrieveResponse;
use App\Exception\NotFoundException;
use App\Service\JobApplication\JobApplicationService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetJobApplicationDispatcher
{
    public function __construct(
        private JobApplicationService $jobApplicationService,
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function __invoke(GetJobApplicationQuery $query): JobApplicationRetrieveResponse
    {
        return $this->jobApplicationService->getJobApplication($query);
    }
}
