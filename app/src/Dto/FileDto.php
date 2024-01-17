<?php

declare(strict_types=1);

namespace App\Dto;

class FileDto
{
    public function __construct(
        public string $mimeType,
        public int $size,
        public string $originalName,
        public string $fileExtension,
        public string $pathName
    ) {
    }
}
