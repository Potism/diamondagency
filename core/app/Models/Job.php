<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $dates = ['deadline'];

    public function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id');
    }

    public function experience()
    {
        return $this->belongsTo(JobExperience::class, 'job_experience_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function type()
    {
        return $this->belongsTo(JobType::class);
    }

    public function shift()
    {
        return $this->belongsTo(JobShift::class);
    }

    public function salaryPeriod()
    {
        return $this->belongsTo(SalaryPeriod::class, 'salary_period');
    }


    public function jobApplication()
    {
        return $this->hasMany(JobApply::class, 'job_id');
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

}
