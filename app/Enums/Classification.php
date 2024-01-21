<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class Classification extends Enum
{
    #[Description("NÃO URGENTE")]
    const NOT_URGENT = 1;
    
    #[Description("POUCO URGENTE")]
    const LITTLE_URGENT = 5;

    #[Description("URGENTE")]
    const URGENT = 10;

    #[Description("MUITO URGENTE")]
    const VERY_URGENT = 15;

    #[Description("EMERGÊNCIA")]
    const EMERGENCY = 20;
}
