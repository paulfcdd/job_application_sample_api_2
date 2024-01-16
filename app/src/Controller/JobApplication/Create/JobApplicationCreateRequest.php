<?php

declare(strict_types=1);

namespace App\Controller\JobApplication\Create;

use App\Command\JobApplicationCreate\JobApplicationCreateCommand;
use Symfony\Component\Validator\Constraints as Assert;

readonly class JobApplicationCreateRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(
            min: 2,
            max: 50,
            maxMessage: 'The first name must be at most {{ limit }} characters long'
        )]
        public string $firstName,
        #[Assert\NotBlank]
        #[Assert\Length(
            min: 2,
            max: 50,
            maxMessage: 'The last name must be at most {{ limit }} characters long'
        )]
        public string $lastName,
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        #[Assert\Regex(
            pattern: '/^[\+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/',
            message: 'The phone number is not valid'
        )]
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
