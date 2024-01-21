<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MedicinePrescription extends Model
{
    use SoftDeletes;
    use LogsActivity;
    
    protected $fillable = [
        'medicine_id',
        'prescription_id',
        'amount',
        'doctor_note',
        'technician_note',
        'technician_id',
        'medicine_apresentation',
        'status'
    ];

    public function prescription() : BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medicine() : BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function getActivitylogOptions() :  LogOptions
    {
        return LogOptions::defaults();
    }
}
