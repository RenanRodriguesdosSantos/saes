<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialPrescription extends Model
{
    protected $fillable = [
        'material_id',
        'prescription_id',
        'technician_id',
        'amount',
        'note'
    ];

    public function prescription() : BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function material() : BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}
