<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class CertificateType extends Enum
{
    #[Description('Comparecimento')]
    const ATTENDANCE = 1;

    #[Description('Normal')]
    const NORMAL = 5;
}
