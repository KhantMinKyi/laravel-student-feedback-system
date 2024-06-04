@extends('students.layout')
@section('content')
    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-yellow-100 dark:bg-gray-800 ">
            <div class="text-lg font-bold">Teacher Count</div>
            <div class="text-xl font-bold">{{ $teacher_count }}</div>
        </div>
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-green-100 dark:bg-gray-800">
            <div class="text-lg font-bold">Student Count</div>
            <div class="text-xl font-bold">{{ $student_count }}</div>
        </div>
        <div class="flex items-center justify-center h-24 rounded bg-white dark:bg-gray-800">
            <a href="{{ route('feedback.create') }}"
                class="text-lg text-gray-900 font-semibold p-4 bg-blue-300 hover:bg-blue-400 rounded-lg">Feedback <i
                    class="fa fa-solid fa-plus text-gray-700"></i></a>

        </div>
    </div>
    <div class="flex justify-center h-48 mb-4 rounded-xl bg-green-50 dark:bg-gray-800 ">
        <table class="w-full text-sm text-left rtl:text-right rounded-lg text-gray-500 dark:text-gray-400">
            <thead
                class="text-md text-gray-700 uppercase bg-green-100 dark:bg-gray-700 dark:text-gray-400 table-header-group">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Subject
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Teaching Semester
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Teacher
                    </th>
                    <th scope="col" class="px-10 py-3">
                        phone
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($current_learning_courses as $no => $current_learning_course)
                    @isset($current_learning_course)
                        <tr
                            class=" border-b border-green-100 dark:bg-green-800 dark:border-green-700 hover:bg-green-100 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">{{ $no + 1 }}</td>
                            <td class="px-6 py-4">{{ $current_learning_course->courses->course_name }}</td>
                            <td class="px-6 py-4">Semester - {{ $current_learning_course->courses->semester }}</td>
                            <td class="px-6 py-4">{{ $current_learning_course->teacher->name }}</td>
                            <td class="px-6 py-4">{{ $current_learning_course->teacher->phone }}</td>
                        </tr>
                    @endisset
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-red-100 dark:bg-gray-800">
            <div class="text-lg font-bold">Current Year</div>
            <div class="text-xl font-bold">{{ $current_learning_courses[0]->teaching_year }} -
                {{ $current_learning_courses[0]->teaching_year_second_semester }}</div>
        </div>
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-green-100 dark:bg-gray-800">
            <div class="text-lg font-bold">Current Role Number</div>
            <div class="text-xl font-bold">{{ $current_year->role_number }}</div>
        </div>
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-teal-100 dark:bg-gray-800">
            <div class="text-lg font-bold">Total Learning Subject</div>
            <div class="text-xl font-bold">{{ count($current_learning_courses) }}</div>
        </div>
    </div>
    <div class="flex items-center justify-center h-48 mb-4 rounded bg-gray-50 dark:bg-gray-800">
        <p class="text-2xl text-gray-400 dark:text-gray-500">
            <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 18 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 1v16M1 9h16" />
            </svg>
        </p>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div class="flex items-center justify-center rounded bg-gray-50 h-28 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded bg-gray-50 h-28 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded bg-gray-50 h-28 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded bg-gray-50 h-28 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
    </div>
@endsection
