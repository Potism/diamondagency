<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employer extends Authenticatable
{
    use HasFactory;
    protected $table = "employers";
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'socialMedia' => 'object',
        'ver_code_send_at' => 'datetime'
    ];

    protected $guarded = [];

    public function scopeActive()
    {
        return $this->where('status', 1);
    }

    public function scopeBanned()
    {
        return $this->where('status', 0);
    }

    public function scopeEmailUnverified()
    {
        return $this->where('ev', 0);
    }

    public function scopeSmsUnverified()
    {
        return $this->where('sv', 0);
    }
    public function scopeEmailVerified()
    {
        return $this->where('ev', 1);
    }

    public function scopeSmsVerified()
    {
        return $this->where('sv', 1);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id','desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status','!=',0);
    }


    public function jobs()
    {
        return $this->hasMany(Job::class)->where('status', 1)->whereDate('deadline','>', Carbon::now()->toDateTimeString());
    }


    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }

    public function numberOrEmployee()
    {
        return $this->belongsTo(NumberOfEmployees::class, 'number_of_employees_id');
    }

    public function tempjobs()
    {
        return $this->hasMany(TempJob::class)->where('status', 1);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}
