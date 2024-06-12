@extends('students.layout')

@section('content')
    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-blue-50 dark:bg-gray-800">
            <div class="text-lg font-semibold text-gray-600">Name</div>
            <div class="text-xl font-bold">{{ $user->name }}</div>
        </div>
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-blue-50 dark:bg-gray-800">
            <div class="text-lg font-semibold text-gray-600">Registration Number</div>
            <div class="text-xl font-bold">{{ $user->uni_registration_no }}</div>
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
                        Year
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Role Number
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Courses
                    </th>
                    <th scope="col" class="px-10 py-3">
                        Learning Year
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->student_year as $no => $student_year)
                    <tr
                        class=" border-b border-green-100 dark:bg-green-800 dark:border-green-700 hover:bg-green-100 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ $no + 1 }}</td>
                        <td class="px-6 py-4">{{ $student_year->year->year_name }}</td>
                        <td class="px-6 py-4">{{ $student_year->role_number }}</td>
                        <td class="px-6 py-4">
                            @foreach ($student_year->year->courses as $course)
                                {{ $course->course_name }} <br>
                            @endforeach

                        </td>
                        <td class="px-6 py-4">{{ $student_year->learning_year }}</td>
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
    {{-- <div class="flex items-center justify-center h-48 mb-4 rounded bg-gray-50 dark:bg-gray-800">
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
    </div> --}}
@endsection
