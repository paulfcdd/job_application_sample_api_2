<?php

declare(strict_types=1);

namespace App\Query\GetJobApplication;

use App\Controller\JobApplication\Create\JobApplicationCreateRequest;
use App\Controller\JobApplication\Retrieve\JobApplicationRetrieveResponse;
use App\Entity\JobApplication;
use App\Exception\NotFoundException;
use App\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetJobApplicationDispatcher
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FileService $fileService
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function __invoke(GetJobApplicationQuery $query): JobApplicationRetrieveResponse
    {
        $jobApplication = $this->entityManager->getRepository(JobApplication::class)->findOneBy(['id' => $query->id]);
        if (!$jobApplication) {
            throw new NotFoundException('Job application not found');
        }

        if (!$jobApplication->isIsRead()) {
            $jobApplication->setIsRead(true);
            $this->entityManager->flush();
        }

        $url = null;
        if ($jobApplication->getFile()) {
            $url = $this->fileService->generatePublicUrl($jobApplication->getFile()->getName());
        }

        return new JobApplicationRetrieveResponse(
            $jobApplication->getFirstName(),
            $jobApplication->getLastName(),
            $jobApplication->getEmail(),
            $jobApplication->getPhone(),
            $jobApplication->getExpectedSalary(),
            $jobApplication->getPosition()->getName(),
            $jobApplication->getLevel(),
            $url
        );
    }
}
