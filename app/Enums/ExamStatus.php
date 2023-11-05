<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class ExamStatus extends Enum
{
    #[Description('Pendente')]
    const PENDING = 1;

    #[Description('Coletado / Realizado')]
    const COLLECTED = 5;

    #[Description('Não Coletado / Não Realizado')]
    const NO_COLLECTED = 10;

    #[Description('Necessita de Recoleta')]
    const RECOLLECT = 15;
}
