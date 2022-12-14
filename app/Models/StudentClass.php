<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'kelas_id',
        'student_id',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}
