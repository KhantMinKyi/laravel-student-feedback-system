@extends('admins.layout')

@section('content')
    <div class="grid grid-cols-3">
        <div>

        </div>


        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">


            <form class="max-w-sm mx-auto"
                action="{{ route('feedback_template.update', ['feedback_template' => $feedback_template->feedback_template_id]) }}"
                method="POST">
                @csrf
                @method('PUT')
                <div class="mb-5">
                    <label for="feedback_template_question"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Questions </label>
                    <textarea type="text" name="feedback_template_question"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter Questions Sepreate With , " required>{{ $feedback_template->feedback_template_question }}</textarea>
                </div>
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update</button>
            </form>

        </div>

    </div>
@endsection
