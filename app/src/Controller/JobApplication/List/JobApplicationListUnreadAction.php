<?php

declare(strict_types=1);

namespace App\Controller\JobApplication\List;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/job-application/unread', name: 'api.job_application.list.unread', methods: ['GET'])]
class JobApplicationListUnreadAction extends JobApplicationListBaseAction
{
    public function __invoke(Request $request): JsonResponse
    {
        return $this->handleRequest($request, $this->getIsRead());
    }

    #[\Override]
    protected function getIsRead(): bool
    {
        return false;
    }
}
