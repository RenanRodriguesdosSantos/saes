<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exam extends Model
{
    use HasFactory;

    public function scopeType(Builder $query, $type) : Builder
    {
        return $query->where('type', $type);
    }

    public function appointments() : BelongsToMany
    {
        return $this->belongsToMany(Appointment::class);
    }
}
