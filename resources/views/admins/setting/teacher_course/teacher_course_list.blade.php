@extends('admins.layout')
@section('content')
    <div class="flex flex-row-reverse mb-2">
        <a href="{{ route('teacher_course.create') }}"
            class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Add
            Teacher's Course</a>
    </div>
    {{-- {{ $teacher_courses }} --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Teacher name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Courses
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Year
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
                            {{ $teacher['teacher']->name }}
                        </th>

                        <td class="px-6 py-4">
                            @foreach ($teacher['teacher_courses'] as $teacher_course)
                                <b>{{ $teacher_course->courses->course_name }} </b>
                                ({{ $teacher_course->courses->year->year_name }})
                                (Semester{{ $teacher_course->courses->year->semester }})
                                <br>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            @foreach ($teacher['teacher_courses'] as $teacher_course)
                                {{ $teacher_course->teaching_year }} <br>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-end">
                            <a href="{{ route('teacher_course.edit', ['teacher_course' => $teacher['teacher']]) }}"
                                class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">Edit</a>
                        </td>
                        <td>
                            <form action="{{ route('teacher_course.destroy', ['teacher_course' => $teacher['teacher']]) }}"
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
