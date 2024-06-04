@extends('admins.layout')
@section('content')
    <div class="flex flex-row-reverse mb-2">
        <a href="{{ route('user.create') }}"
            class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Add
            User</a>
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
                    <th scope="col" class="px-10 py-3 text-end">

                    </th>
                    <th></th>
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
                            @if (isset($teacher_course->teacher_courses))
                                @foreach ($teacher_course->teacher_courses as $teacher_course)
                                    {{ $teacher_course->courses->course_name }} -
                                    {{ $teacher_course->courses->year->year_name }}
                                    (Semester - {{ $teacher_course->courses->semester }})
                                    <br>
                                @endforeach
                            @endif
                        </td>
                        <td class="px-6 py-4 text-end">
                            <a href="{{ route('user.edit', ['user' => $teacher->id]) }}"
                                class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">Edit</a>
                        </td>
                        <td>
                            <form action="{{ route('user.destroy', ['user' => $teacher->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
