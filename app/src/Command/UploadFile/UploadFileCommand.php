<?php

declare(strict_types=1);

namespace App\Command\UploadFile;

use App\Command\CommandInterface;
use App\Dto\FileDto;
use App\Entity\JobApplication;

class UploadFileCommand implements CommandInterface
{
    public function __construct(
        public JobApplication $jobApplication,
        public FileDto $fileDto
    ) {
    }
}
