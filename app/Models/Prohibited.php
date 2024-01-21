<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Prohibited extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected  $fillable = [
        'receptionist_id',
        'service_id'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo('users', 'receptionist_id');
    }

    public function getActivitylogOptions() :  LogOptions
    {
        return LogOptions::defaults();
    }
}
