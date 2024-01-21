<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Service extends Model
{
      use HasFactory;
      use SoftDeletes;
      use LogsActivity;

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

      public function prohibited() : HasOne
      {
         return $this->hasOne(Prohibited::class);
      }

      public function screening() : HasOne
      {
         return $this->hasOne(Screening::class);
      }

      public function appointments() : HasMany
      {
         return $this->hasMany(Appointment::class);
      }

      public function vitalSigns() : HasMany
      {
         return $this->hasMany(VitalSigns::class);
      }

      public function getActivitylogOptions(): LogOptions
      {
         return LogOptions::defaults();
      }
}
