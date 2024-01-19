<?php

declare(strict_types=1);

namespace App\Service\JobApplication;

use App\Command\CommandHandlerInterface;
use App\Command\JobApplicationCreate\JobApplicationCreateCommand;
use App\Command\UploadFile\UploadFileCommand;
use App\Controller\JobApplication\Retrieve\JobApplicationRetrieveResponse;
use App\Dto\FileDto;
use App\Entity\JobApplication;
use App\Exception\BadRequestException;
use App\Exception\NotFoundException;
use App\Query\GetJobApplication\GetJobApplicationQuery;
use App\Query\GetJobApplicationList\GetJobApplicationListQuery;
use App\Repository\JobApplicationRepository;
use App\Repository\PositionRepository;
use App\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class JobApplicationService
{
    public function __construct(
        private CommandHandlerInterface  $commandHandler,
        private JobLevelCalculator       $calculator,
        private EntityManagerInterface   $entityManager,
        private ParameterBagInterface    $parameterBag,
        private JobApplicationRepository $jobApplicationRepository,
        private FileService              $fileService,
        private PositionRepository $positionRepository,
    )
    {
    }

    /**
     * @throws BadRequestException
     */
    public function createJobApplication(JobApplicationCreateCommand $command): void
    {
        $position = $this->positionRepository->findOneBy(['id' => $command->position]);

        if (!$position) {
            throw new BadRequestException(sprintf('Position code `%s` is not allowed', $command->position));
        }

        $level = $this->calculator->determineJobLevel($command->expectedSalary);
        $jobApplication = new JobApplication();
        $jobApplication
            ->setEmail($command->email)
            ->setFirstName($command->firstName)
            ->setExpectedSalary($command->expectedSalary)
            ->setLastName($command->lastName)
            ->setPhone($command->phone)
            ->setPosition($position)
            ->setLevel($level->value);

        $this->entityManager->persist($jobApplication);
        $this->entityManager->flush();

        $this->uploadFile($jobApplication, $command->file);
    }

    public function getJobApplicationList(GetJobApplicationListQuery $query): array
    {
        $limit = $this->parameterBag->get('records_per_page');
        $list = $this->jobApplicationRepository->getList($query->isRead, $limit, $query->sort, $query->order, $query->page, $query->position);
        $total = $this->jobApplicationRepository->getTotal($query->isRead, $query->position);
        $pages = (int)ceil($total / $limit);

        foreach ($list as $key => $item) {
            if (isset($item['cv'])) {
                $list[$key]['cv'] = $this->fileService->generatePublicUrl($item['cv']);
            }
        }

        return [
            'pages' => $pages,
            'total' => $total,
            'list' => $list,
        ];
    }

    public function getJobApplication(GetJobApplicationQuery $query): JobApplicationRetrieveResponse
    {
        $jobApplication = $this->jobApplicationRepository->findOneBy(['id' => $query->id]);
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
