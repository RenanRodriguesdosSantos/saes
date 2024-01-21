<?php

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

final class UserRole extends Enum
{
    #[Description('Recepcionista')]
    const RECEPTIONIST = 'receptionist';

    #[Description('Enfermeiro (a)')]
    const NURSE = 'nurse';

    #[Description('Médico (a)')]
    const DOCTOR = 'doctor';
    
    #[Description('Técnico (a) de Enfermagem')]
    const NURSING_TECHNICIAN = 'nursing_technician';
}
