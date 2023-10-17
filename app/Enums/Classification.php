<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Classification extends Enum
{
    const NOT_URGENT = 1;
    const LITTLE_URGENT = 5;
    const URGENT = 10;
    const VERY_URGENT = 15;
    const EMERGENCY = 20;
}
