<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class CertificateActivity extends Enum
{
    #[Description('Laborais')]
    const LABOR = 1;
}
