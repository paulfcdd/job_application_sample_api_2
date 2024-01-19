<?php

declare(strict_types=1);

namespace App\Controller\JobApplication\Create;

use App\Command\CommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: 'api/job-application', name: 'api.job_application.create', methods: ['POST'])]
class JobApplicationCreateAction extends AbstractController
{
    public function __construct(
        private readonly CommandHandler $commandHandler,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] JobApplicationCreateRequest $request,
        Request $httpRequest
    ): JsonResponse
    {
        $file = $httpRequest->files->get('file');
        $request->file = $file;
        $this->commandHandler->handle($request->getCommand());

        return $this->json([
            'message' => 'Your application was successfully submitted'
        ]);
    }
}
