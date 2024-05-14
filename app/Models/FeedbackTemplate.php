<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackTemplate extends Model
{
    use HasFactory;
    protected $primaryKey = 'feedback_template_id';
    protected $fillable = [
        'feedback_template_question',
        'date',
        'created_user_id',
    ];
    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }
}
