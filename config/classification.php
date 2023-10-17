<?php

use App\Enums\Classification;

return [
    'colors' => [
        Classification::NOT_URGENT => 'blue',
        Classification::LITTLE_URGENT => 'green',
        Classification::URGENT => 'yellow',
        Classification::VERY_URGENT => 'orange',
        Classification::EMERGENCY => 'red',
    ],
    'descriptions' => [
        Classification::NOT_URGENT => 'NÃO URGENTE',
        Classification::LITTLE_URGENT => 'POUCO URGENTE',
        Classification::URGENT => 'URGENTE',
        Classification::VERY_URGENT => 'MUITO URGENTE',
        Classification::EMERGENCY => 'EMERGÊNCIA',
    ]
];