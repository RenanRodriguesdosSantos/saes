<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Classification extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;
    
    public function discriminator() : BelongsTo
    {
       return $this->belongsTo(Discriminator::class);
    }

    public function flowchart() : BelongsTo
    {
       return $this->belongsTo(Flowchart::class);
    }

    public function getActivitylogOptions() :  LogOptions
    {
        return LogOptions::defaults();
    }
}
