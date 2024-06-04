<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_name',
        'year_id',
        'semester',
    ];
    public function year()
    {
        return $this->belongsTo(Year::class);
    }
    public function teachers()
    {
        return $this->belongsToMany(User::class);
    }
}
