<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'birthdate',
        'profile_image',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'updated_at' => 'datetime',
    ];

    // คำนวณอายุแบบ dynamic (accessor)
    public function getAgeAttribute()
    {
        return \Carbon\Carbon::parse($this->birthdate)->age;
    }
}

