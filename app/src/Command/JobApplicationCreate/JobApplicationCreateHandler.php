<?php

declare(strict_types=1);

namespace App\Command\JobApplicationCreate;

use App\Entity\JobApplication;
use App\Query\GetPositionByCode\GetPositionByCodeQuery;
use App\Query\QueryDispatcher;
use App\Service\JobApplication\JobLevelCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class JobApplicationCreateHandler
{
    public function __construct(
        private QueryDispatcher        $queryDispatcher,
        private JobLevelCalculator     $calculator,
        private EntityManagerInterface $entityManager,
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
    }
}
