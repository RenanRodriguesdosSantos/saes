<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class MedicineType extends Enum
{
    #[Description('Medicamento')]
    const MEDICINE = 1;

    #[Description('Procedimento')]
    const PROCEDURE = 5;
}
