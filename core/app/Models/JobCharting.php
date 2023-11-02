<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCharting extends Model
{
    use HasFactory;

    public function job()
    {
        return $this->hasMany(Job::class, 'charting_id')->where('status', 1);
    }
}
