<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    public function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id');
    }

    public function jobApplication()
    {
        return $this->belongsTo(JobApply::class, 'job_apply_id');
    }
}
