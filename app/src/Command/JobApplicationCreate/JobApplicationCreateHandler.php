<?php

declare(strict_types=1);

namespace App\Command\JobApplicationCreate;

use App\Service\JobApplication\JobApplicationService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class JobApplicationCreateHandler
{
    public function __construct(
        private JobApplicationService $jobApplicationService,
    ) {
    }

    public function __invoke(JobApplicationCreateCommand $command): void
    {
        $this->jobApplicationService->createJobApplication($command);
    }
}
