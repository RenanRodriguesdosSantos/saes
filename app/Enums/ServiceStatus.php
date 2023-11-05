<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ServiceStatus extends Enum
{
    const PROHIBITED = 1;
    const SCREENING = 5;
    const APPOINTMENT = 10;
    const FINISHED = 15;
    const CANCELED = 20;
}
