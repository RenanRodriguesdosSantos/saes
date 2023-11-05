<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class ExamType extends Enum
{
    #[Description('imagem')]
    const IMAGE = 1;

    #[Description('laboratorial')]
    const LABORATORY = 5;
}
