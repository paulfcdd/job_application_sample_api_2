<?php

declare(strict_types=1);

namespace App\Controller\JobApplication\Create;

use App\Command\JobApplicationCreate\JobApplicationCreateCommand;
use Symfony\Component\Validator\Constraints as Assert;

readonly class JobApplicationCreateRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $firstName,
        #[Assert\NotBlank]
        public string $lastName,
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        public string $phone,
        #[Assert\NotBlank]
        public int $expectedSalary,
        #[Assert\NotBlank]
        public string $position
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
            $this->position
        );
    }
}
