<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicinePrescription extends Model
{
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
}
