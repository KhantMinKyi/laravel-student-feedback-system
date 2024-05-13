@extends('admins.layout')
@section('content')
    <div class="flex flex-row-reverse mb-2">
        <a href="{{ route('student_year.create') }}"
            class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Add
            Student's Year</a>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Student Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Role Number
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Semester
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Attending Year
                    </th>
                    <th scope="col" class="px-10 py-3 text-end">

                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($student_years as $student_year)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $student_year->student->name }}
                        </th>

                        <td class="px-6 py-4">
                            {{ $student_year->role_number }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $student_year->year->year_name }} ( Semester - {{ $student_year->year->semester }})
                        </td>
                        <td class="px-6 py-4">
                            {{ $student_year->learning_year }}
                        </td>
                        <td class="px-6 py-4 text-end">
                            <a href="{{ route('student_year.edit', ['student_year' => $student_year->student_year_id]) }}"
                                class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">Edit</a>
                        </td>
                        <td>
                            <form
                                action="{{ route('student_year.destroy', ['student_year' => $student_year->student_year_id]) }}"
                                method="POST">
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
