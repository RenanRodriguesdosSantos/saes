<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalSigns extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_glucose',
        'heart_rate',
        'saturation',
        'temperature',
        'blood_pressure',
        'weight',
        'glasgow',
        'service_id',
        'nurse_id'
    ];
}
