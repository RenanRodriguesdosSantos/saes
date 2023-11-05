<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class Gender extends Enum
{
    #[Description('Feminino')]
    const FEMININE = 'f';

    #[Description('Masculino')]
    const MASCULINE = 'm';
}
