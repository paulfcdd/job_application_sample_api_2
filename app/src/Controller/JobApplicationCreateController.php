<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: 'api/job-application', name: 'api.job_application.create', methods: ['POST'])]
class JobApplicationCreateController extends AbstractController
{
    public function __invoke(Request $request): JsonResponse
    {
        return $this->json(['create']);
    }
}
