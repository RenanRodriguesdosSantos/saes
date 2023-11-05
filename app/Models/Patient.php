<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mother',
        'father',
        'birth_date',
        'gender',
        'cpf',
        'cns',
        'phone',
        'rg',
        'profission',
        'neighborhood',
        'place',
        'residence_number',
        'complement',
        'county_id',
        'naturalness_id',
        'ethnicity_id'
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    public function ethnicity() : BelongsTo
    {
        return $this->belongsTo(Ethnicity::class);
    }

    public function county() : BelongsTo
    {
        return $this->belongsTo(County::class);
    }

    public function naturalness() : BelongsTo
    {
        return $this->belongsTo(County::class, 'naturalness_id');
    }

    public function services() : HasMany
    {
        return $this->hasMany(Service::class);
    }
}
