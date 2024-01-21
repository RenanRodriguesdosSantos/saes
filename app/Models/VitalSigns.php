<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class VitalSigns extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

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

    public function getActivitylogOptions() :  LogOptions
    {
        return LogOptions::defaults();
    }
}
