<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.user_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreUpdateRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);
        return redirect('/admin');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back();
        }
        return view('admins.user_edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validated = $request->validate([
            'name' => 'required|string',
            'username' => ['required', 'string', Rule::unique('users', 'username')->ignore($id, 'id')],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id, 'id')],
            'dob' => 'required|date',
            'uni_registration_no' => 'nullable|string',
            'is_hod' => 'nullable|boolean',
            'address' => 'required|string',
            'father_name' => 'required|string',
            'nrc' => 'required|string',
            'gender' => 'required|string',
            'phone' => 'required|string',
            'type' => 'required|string',
        ]);
        $user = User::find($id);
        if (!$user) {
            return redirect()->back();
        }
        $user->update($validated);
        return redirect('/admin');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::find($id)->delete();
        return redirect()->route('admin.index');
    }
    public function teacherList()
    {
        $teachers = User::where('type', 'teacher')->get();
        // foreach ($teachers as $teacher) {
        //     return $teacher->getOneTeacherWithCourses($teacher->id);
        //     foreach ($teacher->getOneTeacherWithCourses($teacher->id) as $courses) {
        //         return $courses;
        //     }
        // }

        return view('admins.teacher.teacher_list', compact('teachers'));
    }
    public function studentList()
    {
        $students = User::where('type', 'student')->get();

        return view('admins.student.student_list', compact('students'));
    }
}
