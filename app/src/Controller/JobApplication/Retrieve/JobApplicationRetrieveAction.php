<?php

declare(strict_types=1);

namespace App\Controller\JobApplication\Retrieve;

use App\Query\GetJobApplication\GetJobApplicationQuery;
use App\Query\QueryDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/job-application/{id}', name: 'api.job_application.retrieve', methods: ['GET'])]
class JobApplicationRetrieveAction extends AbstractController
{
    public function __construct(
        private readonly QueryDispatcherInterface $queryDispatcher,
    ) {
    }

    public function __invoke(int $id): JsonResponse
    {
        /** @var JobApplicationRetrieveResponse $jobInterview */
        $jobApplication = $this->queryDispatcher->dispatch(new GetJobApplicationQuery($id));

        return $this->json(['jobApplication' => $jobApplication]);
    }
}
