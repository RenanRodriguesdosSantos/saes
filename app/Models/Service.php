<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Service extends Model
{
      use HasFactory;

      protected $fillable = [
         'status',
         'patient_id',
         'prohibited_id',
         'screening_id',
         'appointment_id',
      ];

      public function patient() : BelongsTo
      {
         return $this->belongsTo(Patient::class);
      }

      public function prohibited() : BelongsTo
      {
         return $this->belongsTo(Prohibited::class);
      }

      public function screening() : BelongsTo
      {
         return $this->belongsTo(Screening::class);
      }

      public function appointments() : HasMany
      {
         return $this->hasMany(Appointment::class);
      }

      public function vitalSigns() : HasMany
      {
         return $this->hasMany(VitalSigns::class);
      }
}
