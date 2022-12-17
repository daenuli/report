<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExtra extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'student_class_id',
        'extra_id',
        'note'
    ];

    public function extra()
    {
        return $this->belongsTo(Extracurricular::class, 'extra_id');
    }
}
