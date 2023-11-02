<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobShift extends Model
{
    use HasFactory;


    public function job()
    {
        return $this->hasMany(Job::class, 'shift_id')->where('status', 1);
    }
}
