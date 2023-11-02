<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSoftware extends Model
{
    use HasFactory;

    public function job()
    {
        return $this->hasMany(Job::class, 'software_id')->where('status', 1);
    }
}
