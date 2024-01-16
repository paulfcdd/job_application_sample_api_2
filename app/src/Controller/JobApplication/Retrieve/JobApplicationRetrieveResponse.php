<?php

declare(strict_types=1);

namespace App\Controller\JobApplication\Retrieve;

class JobApplicationRetrieveResponse
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $phone,
        public int $expectedSalary,
        public string $position,
        public string $level,
    ) {
    }
}
