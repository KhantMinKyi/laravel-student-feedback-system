@extends('teachers.layout')
@section('content')
    <div class="flex flex-row-reverse mb-2">

    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Course Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Semester
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Feedback Questions
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Feedback Answers
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Academic Year
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Feedback Date
                    </th>
                    <th scope="col" class="px-6 py-3">

                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($feedbacks as $no => $feedback)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td class="px-6 py-4">
                            {{ $no + 1 }}
                        </td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $feedback->course->course_name }}
                        </th>
                        <td class="px-6 py-4">
                            Semester - {{ $feedback->course->semester }}
                        </td>
                        <td class="px-6 py-4">
                            @foreach ($feedback->data as $data)
                                {{ $data['feedback_question'] }} <br>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            @foreach ($feedback->data as $data)
                                {{ $data['feedback_answer'] }} <br>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            {{ $feedback->learning_year }} - {{ $feedback->learning_year_second_semester }}
                        </td>
                        <td class="px-6 py-4">
                            {{ date('d-m-Y', strToTime($feedback->feedback_date)) }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('teacher.feedback.detail', ['id' => $feedback->feedback_id]) }}">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
