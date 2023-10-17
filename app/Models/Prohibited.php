<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prohibited extends Model
{
    use HasFactory;

    protected  $fillable = [
        'receptionist_id'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo('users', 'receptionist_id');
    }
}
