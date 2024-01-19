<?php

declare(strict_types=1);

namespace App\Command\UploadFile;

use App\Service\FileService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class UploadFileHandler
{
    public function __construct(
        private FileService $fileService,
    ) {
    }

    public function __invoke(UploadFileCommand $command): void
    {
        $this->fileService->upload($command);
    }
}
