<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MaterialPrescription extends Model
{
    use SoftDeletes;
    use LogsActivity;

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

    public function getActivitylogOptions() :  LogOptions
    {
        return LogOptions::defaults();
    }
}
