<?php

declare(strict_types=1);

namespace App\Command\JobApplicationCreate;

use App\Command\CommandInterface;

class JobApplicationCreateCommand implements CommandInterface
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $phone,
        public int $expectedSalary,
        public string $position
    ) {
    }
}
