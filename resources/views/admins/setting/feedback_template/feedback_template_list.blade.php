@extends('admins.layout')
@section('content')
    <div class="flex flex-row-reverse mb-2">
        <a href="{{ route('feedback_template.create') }}"
            class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Add
            Feedback Template</a>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Created User
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Created Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Questions
                    </th>
                    <th scope="col" class="px-10 py-3 text-end">

                    </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($feedback_templates as $feedback_template)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $feedback_template->created_user->name }}
                        </th>

                        <td class="px-6 py-4">
                            {{ $feedback_template->date }}
                        </td>
                        <td class="px-6 py-4">
                            <?php
                            $questionArr = explode(',', $feedback_template->feedback_template_question);
                            ?>
                            @foreach ($questionArr as $question)
                                {{ $question }} <br>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-end">
                            <a href="{{ route('feedback_template.edit', ['feedback_template' => $feedback_template->feedback_template_id]) }}"
                                class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">Edit</a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
