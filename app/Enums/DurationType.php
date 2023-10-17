<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class DurationType extends Enum
{
    #[Description('Dias')]
    const DAYS = 1;

    #[Description('Meses')]
    const MONTHS = 5;
}
