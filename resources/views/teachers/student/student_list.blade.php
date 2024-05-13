@extends('teachers.layout')
@section('content')
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Student name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        phone
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Year
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Role Number
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $student->name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $student->phone }}
                        </td>
                        <td class="px-6 py-4 font-bold">
                            @foreach ($student->student_year as $year)
                                {{ $year->year->year_name }} (Semester - {{ $year->year->semester }}) <br>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 font-bold">
                            @foreach ($student->student_year as $year)
                                {{ $year->role_number }} <br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
