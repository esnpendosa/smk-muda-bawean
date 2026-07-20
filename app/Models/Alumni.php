<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'full_name',
        'graduation_year',
        'email',
        'phone',
        'address',
    ];

    public function tracerStudies(): HasMany
    {
        return $this->hasMany(TracerStudy::class, 'alumni_id');
    }
}
