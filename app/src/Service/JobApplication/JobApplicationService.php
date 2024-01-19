<?php

declare(strict_types=1);

namespace App\Service\JobApplication;

use App\Command\CommandHandlerInterface;
use App\Command\JobApplicationCreate\JobApplicationCreateCommand;
use App\Command\UploadFile\UploadFileCommand;
use App\Dto\FileDto;
use App\Entity\JobApplication;
use App\Query\GetPositionByCode\GetPositionByCodeQuery;
use App\Query\QueryDispatcherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class JobApplicationService
{
    public function __construct(
        private QueryDispatcherInterface $queryDispatcher,
        private CommandHandlerInterface $commandHandler,
        private JobLevelCalculator $calculator,
        private EntityManagerInterface $entityManager,

    ) {
    }

    public function createJobApplication(JobApplicationCreateCommand $command): void
    {
        $position = $this->queryDispatcher->dispatch(new GetPositionByCodeQuery($command->position));
        $level = $this->calculator->determineJobLevel($command->expectedSalary);
        $jobApplication = new JobApplication();
        $jobApplication
            ->setEmail($command->email)
            ->setFirstName($command->firstName)
            ->setExpectedSalary($command->expectedSalary)
            ->setLastName($command->lastName)
            ->setPhone($command->phone)
            ->setPosition($position)
            ->setLevel($level->value)
        ;

        $this->entityManager->persist($jobApplication);
        $this->entityManager->flush();

        $this->uploadFile($jobApplication, $command->file);
    }

    private function uploadFile(JobApplication $jobApplication, ?UploadedFile $uploadedFile): void
    {
        if ($uploadedFile) {
            $fileDto = new FileDto(
                $uploadedFile->getMimeType(),
                $uploadedFile->getSize(),
                $uploadedFile->getClientOriginalName(),
                $uploadedFile->guessExtension(),
                $uploadedFile->getPathname()
            );
            $uploadFileCommand = new UploadFileCommand($jobApplication, $fileDto);
            $this->commandHandler->handle($uploadFileCommand);
        }
    }
}
