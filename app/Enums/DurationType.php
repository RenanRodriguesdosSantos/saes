<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class DurationType extends Enum
{
    #[Description('dias')]
    const DAYS = 1;

    #[Description('meses')]
    const MONTHS = 5;
}
