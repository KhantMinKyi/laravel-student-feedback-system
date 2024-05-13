<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'dob',
        'uni_registration_no',
        'type',
        'is_hod',
        'address',
        'father_name',
        'nrc',
        'gender',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public static function teachers()
    {
        return User::where('type', 'teacher')->get();
    }
    public static function getOneTeacherWithCourses($id)
    {
        $teacher = User::find($id);
        $data = new \stdClass;

        $teacher_courses = TeacherCourse::with('courses')->where('teacher_id', $teacher->id)->get();
        if (count($teacher_courses) > 0) {
            $data->teacher = $teacher;
            $data->teacher_courses = $teacher_courses;
        }

        return $data;
    }
    public function student_year()
    {
        return $this->hasMany(StudentYear::class);
    }
}
