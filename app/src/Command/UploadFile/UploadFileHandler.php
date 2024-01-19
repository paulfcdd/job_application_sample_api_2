<?php

declare(strict_types=1);

namespace App\Command\UploadFile;

use App\Entity\File;
use App\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class UploadFileHandler
{
    public function __construct(
        private FileService            $fileService,
        private EntityManagerInterface $entityManager,
    )
    {

    }
    public function __invoke(UploadFileCommand $command): void
    {
        $fileName = $this->fileService->upload($command->fileDto);
        $file = new File();
        $file->setJobApplication($command->jobApplication);
        $file->setMimeType($command->fileDto->mimeType);
        $file->setName($fileName);
        $file->setOriginalName($command->fileDto->originalName);
        $file->setSize($command->fileDto->size);

        $this->entityManager->persist($file);
        $this->entityManager->flush();
    }
}
