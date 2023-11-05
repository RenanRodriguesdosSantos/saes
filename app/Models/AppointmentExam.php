<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentExam extends Model
{
    protected $table = "appointment_exam";

    protected $fillable = [
        'doctor_note',
        'technician_note',
        'appointment_id',
        'exam_id',
        'doctor_id',
        'technician_id',
        'status'
    ];

    public function doctor() : BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function appointment() : BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function exam() : BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}
