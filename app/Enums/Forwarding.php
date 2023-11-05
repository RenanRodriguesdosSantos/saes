<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class Forwarding extends Enum
{
    #[Description('Para serviço mais complexo')]
    const FOR_MORE_COMPLEX_SERVICE = 1;

    #[Description('Observação hospitalar')]
    const HOSPITAL_OBSERVATION = 5;

    #[Description('Ambulatório rede pública')]
    const PUBLIC_NETWORK_OUTPATIENT_CLINIC = 10;

    #[Description('Internação')]
    const INTERNMENT = 15;

    #[Description('Retorno')]
    const RETURN = 20;

    #[Description('Alta')]
    const MEDICAL_RELEASE = 25;
}
