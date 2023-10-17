<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classification extends Model
{
    use HasFactory;
    
    public function discriminator() : BelongsTo
    {
       return $this->belongsTo(Discriminator::class);
    }

    public function flowchart() : BelongsTo
    {
       return $this->belongsTo(Flowchart::class);
    }
}
