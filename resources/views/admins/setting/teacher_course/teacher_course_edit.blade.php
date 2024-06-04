@extends('admins.layout')

@section('content')
    <div class=" flex justify-center">
        <div class=" p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">


            <form class="max-w-sm mx-auto" action="{{ route('teacher_course.update', ['teacher_course' => $teacher->id]) }}"
                method="POST">
                @csrf
                @method('PUT')
                <div class="mb-5">
                    <label for="teacher_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teacher
                        Name</label>
                    <input type="text"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter Course Name" value="{{ $teacher->name }}" disabled />
                    <input type="hidden" name="teacher_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter Course Name" required value="{{ $teacher->id }}" />
                </div>
                @foreach ($teacher_courses->teacher_courses as $key => $teacher_course)
                    <div class="mb-5">
                        <label for="Course Name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Course
                            Name</label>
                        <select id="course_id" name="course_id_{{ $teacher_course->teacher_course_id }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">None</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @if ($course->id == $teacher_course->course_id) selected @endif>
                                    {{ $course->course_name }} ({{ $course->year->year_name }} - Semester
                                    {{ $course->year->semester }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-5">
                            <label for="teaching_year"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teaching Year</label>
                            <input type="text" name="teaching_year_{{ $teacher_course->teacher_course_id }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter Teaching Year " value="{{ $teacher_course->teaching_year }}" />
                        </div>
                        <div class="mb-5">
                            <label for="teaching_year_second_semester"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teaching
                                Year 2nd Semester</label>
                            <input type="text"
                                name="teaching_year_second_semester_{{ $teacher_course->teacher_course_id }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter Teaching Year 2nd Sem"
                                value="{{ $teacher_course->teaching_year_second_semester }}" required />
                        </div>
                        <hr>
                    </div>
                @endforeach
                <button type="submit"
                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Update</button>
            </form>

        </div>

    </div>
@endsection
