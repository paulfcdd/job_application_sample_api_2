<?php

declare(strict_types=1);

namespace App\Controller\JobApplication\List;

use App\Query\GetJobApplicationList\GetJobApplicationListQuery;
use App\Query\QueryDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/job-application/unread', name: 'api.job_application.list.unread', methods: ['GET'])]
class JobApplicationListUnreadAction extends AbstractController
{
    public function __construct(
        private readonly QueryDispatcherInterface $queryDispatcher
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $page = (int)$request->query->get('page');
        $query = new GetJobApplicationListQuery(false, $page);
        $result = $this->queryDispatcher->dispatch($query);

        return $this->json($result);
    }
}
