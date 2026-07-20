<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PpdbRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'full_name',
        'birth_place',
        'birth_date',
        'previous_school',
        'parent_name',
        'phone',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];
}
