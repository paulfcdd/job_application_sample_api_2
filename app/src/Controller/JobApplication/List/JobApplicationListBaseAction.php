<?php

declare(strict_types=1);

namespace App\Controller\JobApplication\List;

use App\Query\GetJobApplicationList\GetJobApplicationListQuery;
use App\Query\QueryDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class JobApplicationListBaseAction extends AbstractController
{
    public function __construct(
        private readonly QueryDispatcherInterface $queryDispatcher
    ) {
    }

    abstract protected function getIsRead(): bool;

    protected function handleRequest(Request $request, bool $isRead): JsonResponse
    {
        $page = (int) $request->query->get('page', 1);
        $sort = $request->query->get('sort', 'createdAt');
        $order = $request->query->get('order', 'DESC');
        $position = (int)$request->query->get('position');

        $query = new GetJobApplicationListQuery($isRead, $page, $sort, $order, $position);
        $result = $this->queryDispatcher->dispatch($query);

        return $this->json($result);
    }
}
