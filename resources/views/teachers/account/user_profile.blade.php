@extends('teachers.layout')

@section('content')
    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-blue-50 dark:bg-gray-800">
            <div class="text-lg font-semibold text-gray-600">Name</div>
            <div class="text-xl font-bold">{{ $user->name }}</div>
        </div>
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-blue-50 dark:bg-gray-800">
            <div class="text-lg font-semibold text-gray-600">Username</div>
            <div class="text-xl font-bold">{{ $user->username }}</div>
        </div>
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-blue-50 dark:bg-gray-800">
            <div class="text-lg font-semibold text-gray-600">Phone</div>
            <div class="text-xl font-bold">{{ $user->phone }}</div>
        </div>
    </div>
    <div class="flex justify-center h-48 mb-4  bg-green-50 dark:bg-gray-800 ">
        <table class="w-full text-sm text-left rtl:text-right  text-gray-500 dark:text-gray-400">
            <thead
                class="text-md text-gray-700 uppercase bg-green-100 dark:bg-gray-700 dark:text-gray-400 table-header-group">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Course
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Year
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Semester
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Academic Year
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($teaching_subjects->teacher_courses as $no => $teaching_subject)
                    <tr
                        class=" border-b border-green-100 dark:bg-green-800 dark:border-green-700 hover:bg-green-100 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ $no + 1 }}</td>
                        <td class="px-6 py-4">{{ $teaching_subject->courses->course_name }}</td>
                        <td class="px-6 py-4">{{ $teaching_subject->courses->year->year_name }}</td>
                        <td class="px-6 py-4">Semester - {{ $teaching_subject->courses->semester }}</td>
                        <td class="px-6 py-4">{{ $teaching_subject->teaching_year }} -
                            {{ $teaching_subject->teaching_year_second_semester }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-yellow-50 dark:bg-gray-800">
            <div class="text-lg font-semibold text-gray-600">Username</div>
            <div class="text-xl font-bold">{{ $user->username }}</div>
        </div>
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-yellow-50 dark:bg-gray-800">
            <div class="text-lg font-semibold text-gray-600">Email</div>
            <div class="text-xl font-bold">{{ $user->email }}</div>
        </div>
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-violet-50 dark:bg-gray-800">
            <div class="text-lg font-semibold text-gray-600">Address</div>
            <div class="text-xl font-bold">{{ $user->address }}</div>
        </div>
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-violet-50 dark:bg-gray-800">
            <div class="text-lg font-semibold text-gray-600">NRC</div>
            <div class="text-xl font-bold">{{ $user->nrc }}</div>
        </div>
    </div>
@endsection
