<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Exam extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    public function scopeType(Builder $query, $type) : Builder
    {
        return $query->where('type', $type);
    }

    public function appointments() : BelongsToMany
    {
        return $this->belongsToMany(Appointment::class);
    }

    public function getActivitylogOptions() :  LogOptions
    {
        return LogOptions::defaults();
    }
}
