<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
