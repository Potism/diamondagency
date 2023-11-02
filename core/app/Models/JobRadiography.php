<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRadiography extends Model
{
    use HasFactory;

    public function job()
    {
        return $this->hasMany(Job::class, 'radiography_id')->where('status', 1);
    }
}
