<?php

declare(strict_types=1);

namespace App\Controller\JobApplication\List;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/job-application/read', name: 'api.job_application.list.read', methods: ['GET'])]
class JobApplicationListReadAction extends JobApplicationListBaseAction
{

    public function __invoke(Request $request): JsonResponse
    {
        return $this->handleRequest($request, $this->getIsRead());
    }

    #[\Override]
    protected function getIsRead(): bool
    {
        return true;
    }
}
