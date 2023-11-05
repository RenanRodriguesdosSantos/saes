<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserRole extends Enum
{
    const RECEPTIONIST = 'receptionist';
    const NURSE = 'nurse';
    const DOCTOR = 'doctor';
    const NURSING_TECHNICIAN = 'nursing_technician';
}
