<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobExperience extends Model
{
    use HasFactory;

    public function job()
    {
        return $this->hasMany(Job::class, 'job_experience_id')->where('status', 1);
    }
}
