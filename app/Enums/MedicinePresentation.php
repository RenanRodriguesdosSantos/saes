<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class MedicinePresentation extends Enum
{
    #[Description('Oral')]
    const VO = 'vo';

    #[Description('Sublingual')]
    const SL = 'sl';

    #[Description('Intramuscular')]
    const IM = 'im';

    #[Description('Intravenoso')]
    const IV = 'iv';
}
