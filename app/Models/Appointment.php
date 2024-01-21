<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Appointment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'symptoms',
        'results',
        'conduct',
        'forwarding',
        'doctor_id',
        'service_id'
    ];

    public function diagnoses() : BelongsToMany
    {
        return $this->belongsToMany(Diagnosis::class);
    }

    public function service() : BelongsTo 
    {
        return $this->belongsTo(Service::class);
    }

    public function exams() : BelongsToMany
    {
        return $this->belongsToMany(Exam::class);
    }

    public function examItems() : HasMany
    {
        return $this->hasMany(AppointmentExam::class);
    }

    public function prescriptions() : HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function getActivitylogOptions() :  LogOptions
    {
        return LogOptions::defaults();
    }
}
