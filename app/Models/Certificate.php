<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Certificate extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'duration',
        'duration_type',
        'type',
        'start_at',
        'end_at',
        'activity',
        'show_cids'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime'
    ];

    public function doctor() : BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function appointment() : BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function getActivitylogOptions() :  LogOptions
    {
        return LogOptions::defaults();
    }
}
