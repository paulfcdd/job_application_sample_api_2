<?php

declare(strict_types=1);

namespace App\Enum\JobApplication;

enum Level: string
{
    case JUNIOR = 'Junior';
    case REGULAR = 'Regular';
    case SENIOR = 'Senior';
}
