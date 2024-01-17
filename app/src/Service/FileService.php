<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\FileDto;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\UnableToWriteFile;
use Symfony\Component\Uid\Factory\UuidFactory;

readonly class FileService
{
    public function __construct(
        private FilesystemOperator $filesystem,
        private UuidFactory $uuidFactory
    )
    {
    }

    public function upload(FileDto $fileDto): string
    {
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

            return $fileName;
        } catch (UnableToWriteFile $exception) {
            if (is_resource($stream)) {
                fclose($stream);
            }
            throw new UnableToWriteFile('Failed to upload file.', 0, $exception);
        }
    }
}
