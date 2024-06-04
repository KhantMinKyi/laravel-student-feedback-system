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
                        Course
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Feedback Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Feedback Year
                    </th>
                    <th scope="col" class="px-6 py-3">

                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($feedbacks as $feedback)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $feedback->teacher->name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $feedback->course->course_name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $feedback->feedback_date }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $feedback->year->learning_year }} - {{ $feedback->year->learning_year_second_semester }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('feedback.show', ['feedback' => $feedback->feedback_id]) }}">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
