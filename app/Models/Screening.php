<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Screening extends Model
{
    use HasFactory;

    protected $fillable = [
        'nurse_id',
        'description',
        'classification_id'
    ];

    public function classification() : BelongsTo
    {
        return $this->belongsTo(Classification::class);
    }
}
