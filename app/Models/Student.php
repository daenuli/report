<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'name',
        'address',
        'birth_place',
        'date_of_birth',
        'gender',
        'phone',
        'photo'
    ];
}
