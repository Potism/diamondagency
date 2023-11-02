<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempJob extends Model
{
    use HasFactory;

  //  protected $dates = ['deadline'];

    public function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id');
    }

    public function radiography()
    {
        return $this->belongsTo(JobRadiography::class);
    }

    public function charting()
    {
        return $this->belongsTo(JobCharting::class);
    }

    public function software()
    {
        return $this->belongsTo(JobSoftware::class);
    }
    public function jobApplication()
    {
        return $this->hasMany(JobApply::class, 'job_id')->where('job_type','temp_job');
    }
}
