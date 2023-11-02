<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    use HasFactory;

    public function job()
    {
        return $this->hasMany(Job::class, 'type_id')->where('status', 1);
    }
}
