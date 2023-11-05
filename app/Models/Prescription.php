<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'doctor_id'
    ];

    public function appointment() : BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor() : BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function medicines() : HasMany
    {
        return $this->hasMany(MedicinePrescription::class);
    }

    public function materials() : HasMany
    {
        return $this->hasMany(MaterialPrescription::class);
    }
}
