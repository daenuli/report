<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Student extends Model
{
    const LIMIT = 3;   

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

    public function limit()
    {
        return Str::words($this->address, Student::LIMIT );
    }
}
