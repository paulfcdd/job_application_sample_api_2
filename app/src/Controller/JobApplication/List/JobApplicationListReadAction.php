<?php

declare(strict_types=1);

namespace App\Controller\JobApplication\List;

use App\Query\GetJobApplicationList\GetJobApplicationListQuery;
use App\Query\QueryDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/job-application/read', name: 'api.job_application.list.read', methods: ['GET'])]
class JobApplicationListReadAction extends AbstractController
{
    public function __construct(
        private readonly QueryDispatcherInterface $queryDispatcher
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $page = (int)$request->query->get('page');
        $query = new GetJobApplicationListQuery(true, $page);
        $result = $this->queryDispatcher->dispatch($query);

        return $this->json($result);
    }
}
