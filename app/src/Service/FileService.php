<?php

declare(strict_types=1);

namespace App\Service;

use App\Command\UploadFile\UploadFileCommand;
use App\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\UnableToWriteFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

readonly class FileService
{
    public function __construct(
        private FilesystemOperator $filesystem,
        private UuidFactory $uuidFactory,
        private UrlGeneratorInterface $urlGenerator,
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function upload(UploadFileCommand $uploadFileCommand): void
    {
        $fileDto = $uploadFileCommand->fileDto;
        $fileName = sprintf('%s.%s',
            $this->uuidFactory->create(),
            $fileDto->fileExtension
        );
        $stream = fopen($fileDto->pathName, 'r+');

        try {
            $this->filesystem->writeStream(
                $fileName,
                $stream,
                ['visibility' => 'public']
            );

            if (is_resource($stream)) {
                fclose($stream);
            }

            $this->saveFileToDb($uploadFileCommand, $fileName);
        } catch (UnableToWriteFile $exception) {
            if (is_resource($stream)) {
                fclose($stream);
            }
            throw new UnableToWriteFile('Failed to upload file.', 0, $exception);
        }
    }

    public function generatePublicUrl(string $filename): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $baseURL = $request ? $request->getSchemeAndHttpHost() : '';

        return $baseURL . $this->urlGenerator->generate('app.file_serve', ['fileName' => $filename]);
    }

    private function saveFileToDb(UploadFileCommand $command, string $fileName): void
    {
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
