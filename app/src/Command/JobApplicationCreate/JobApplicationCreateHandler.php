<?php

declare(strict_types=1);

namespace App\Command\JobApplicationCreate;

use App\Command\CommandHandlerInterface;
use App\Command\UploadFile\UploadFileCommand;
use App\Dto\FileDto;
use App\Entity\JobApplication;
use App\Query\GetPositionByCode\GetPositionByCodeQuery;
use App\Query\QueryDispatcherInterface;
use App\Service\JobApplication\JobLevelCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class JobApplicationCreateHandler
{
    public function __construct(
        private QueryDispatcherInterface $queryDispatcher,
        private JobLevelCalculator $calculator,
        private EntityManagerInterface $entityManager,
        private CommandHandlerInterface $commandHandler
    ) {
    }

    public function __invoke(JobApplicationCreateCommand $command): void
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

        if ($command->file) {
            $fileDto = new FileDto(
                $command->file->getMimeType(),
                $command->file->getSize(),
                $command->file->getClientOriginalName(),
                $command->file->guessExtension(),
                $command->file->getPathname()
            );
            $uploadFileCommand = new UploadFileCommand($jobApplication, $fileDto);
            $this->commandHandler->handle($uploadFileCommand);
        }
    }
}
