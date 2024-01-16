<?php

declare(strict_types=1);

namespace App\Enum\JobApplication;

enum Level: string
{
    case JUNIOR = 'junior';
    case REGULAR = 'regular';
    case SENIOR = 'senior';
}
