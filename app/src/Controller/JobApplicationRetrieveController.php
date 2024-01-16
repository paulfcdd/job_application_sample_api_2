<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/job-application/{id}', name: 'api.job_application.retrieve', methods: ['GET'])]
class JobApplicationRetrieveController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        return $this->json(['retrieve']);
    }
}
