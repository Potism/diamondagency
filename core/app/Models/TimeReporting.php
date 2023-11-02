<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeReporting extends Model
{
    use HasFactory;
    protected $casts = [
        'out_time' => 'datetime',
    ];
    public function job_apply()
    {
        return $this->belongsTo(JobApply::class, 'job_applies_id');
    }
}
