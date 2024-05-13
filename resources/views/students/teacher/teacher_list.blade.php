@extends('students.layout')
@section('content')
    <div class="flex flex-row-reverse mb-2">

    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Teacher name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        phone
                    </th>
                    <th scope="col" class="px-10 py-3">
                        Teaching Courses
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($teachers as $teacher)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $teacher->name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $teacher->phone }}
                        </td>
                        <td class="px-6 py-4">
                            <?php
                            $teacher_course = $teacher->getOneTeacherWithCourses($teacher->id);
                            ?>
                            @foreach ($teacher_course->teacher_courses as $teacher_course)
                                {{ $teacher_course->courses->course_name }} <br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
