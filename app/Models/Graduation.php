<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Graduation extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year',
        'student_name',
        'exam_number',
        'program_keahlian',
        'status_kelulusan',
    ];
}
