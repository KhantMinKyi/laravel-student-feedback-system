<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentYear extends Model
{
    protected $primaryKey = 'student_year_id';
    use HasFactory;
    protected $fillable = [
        'student_year_id',
        'student_id',
        'year_id',
        'role_number',
        'learning_year',
    ];
    public function student()
    {
        return $this->belongsTo(User::class);
    }
    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id');
    }
}
