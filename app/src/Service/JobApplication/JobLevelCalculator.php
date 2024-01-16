<?php

declare(strict_types=1);

namespace App\Service\JobApplication;

use App\Enum\JobApplication\Level;

class JobLevelCalculator
{
    public function determineJobLevel(int $expectedSalary): Level {
        return match(true) {
            $expectedSalary < 5000 => Level::JUNIOR,
            $expectedSalary >= 5000 && $expectedSalary <= 9999 => Level::REGULAR,
            default => Level::SENIOR,
        };
    }
}
