<?php

declare(strict_types=1);

namespace App\Controller\JobApplication\Create;

use App\Command\JobApplicationCreate\JobApplicationCreateCommand;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class JobApplicationCreateRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $firstName,
        #[Assert\NotBlank]
        public readonly string $lastName,
        #[Assert\NotBlank]
        #[Assert\Email]
        public readonly string $email,
        #[Assert\NotBlank]
        #[Assert\Regex(
            pattern: '/^[\+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/',
            message: 'The phone number is not valid'
        )]
        public readonly string $phone,
        #[Assert\NotBlank]
        public readonly int $expectedSalary,
        #[Assert\NotBlank]
        public readonly int $position,
        #[Assert\File(
            maxSize: '5M',
            mimeTypes: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
        )]
        public ?UploadedFile $file = null
    ) {
    }

    public function getCommand(): JobApplicationCreateCommand
    {
        return new JobApplicationCreateCommand(
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->phone,
            $this->expectedSalary,
            $this->position,
            $this->file
        );
    }
}
