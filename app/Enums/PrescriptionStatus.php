<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class PrescriptionStatus extends Enum
{
    #[Description('Pendente')]
    const PENDING = 1;

    #[Description('Realizado')]
    const REALIZED = 5;

    #[Description('Recusado')]
    const REFUSED = 15;

    #[Description('Em falta')]
    const LACKING = 20;
}
