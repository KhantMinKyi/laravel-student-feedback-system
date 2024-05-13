<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{

    use HasFactory;
    protected $fillable = [
        'year_name',
        'semester',
    ];
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
    public function student_year()
    {
        return $this->hasMany(StudentYear::class);
    }
}
