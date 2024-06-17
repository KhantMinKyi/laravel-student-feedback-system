@extends('teachers.layout')
@section('content')
    <div class="flex justify-center my-4">
        <div class="min-w-max p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div>
                <h5 class="text-xl my-4 text-center font-bold">Teacher Feedback Form</h5>
                <p class="text-lg my-2 font-semibold">Teacher Name : {{ $feedback->teacher->name }}</p>
                <p class="text-lg my-2 font-semibold">Course : {{ $feedback->course->course_name }}</p>
                <hr class="my-4">
            </div>
            @foreach ($data_array as $key => $data)
                <div class="mb-5">
                    <label for="name"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $data['feedback_question'] }}</label>
                    <div class="grid grid-cols-5">
                        <div class="flex flex-col justify-center items-center">
                            <input type="radio" name="question_{{ $key }}" required value="very_good"
                                @if ($data['feedback_answer'] == 'very_good') checked @endif disabled />
                            <span>Strongly Agree</span>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <input type="radio" name="question_{{ $key }}" required value="good"
                                @if ($data['feedback_answer'] == 'good') checked @endif disabled />
                            <span>Agree</span>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <input type="radio" name="question_{{ $key }}" required value="normal"
                                @if ($data['feedback_answer'] == 'normal') checked @endif disabled />
                            <span>Neutral</span>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <input type="radio" name="question_{{ $key }}" required value="not_bad"
                                @if ($data['feedback_answer'] == 'not_bad') checked @endif disabled />
                            <span>Disagree</span>
                        </div>
                        <div class="flex flex-col justify-center items-center">
                            <input type="radio" name="question_{{ $key }}" required value="bad"
                                @if ($data['feedback_answer'] == 'bad') checked @endif disabled />
                            <span>Strongly Disagree</span>
                        </div>
                    </div>
                    <hr class="my-4">
                </div>
            @endforeach
            <div class="mb-5">
                <label for="feedback_strength_weakness"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Please Comment on the
                    Teacher's
                    Teaching Strengths and Weakness</label>
                <textarea name="feedback_strength_weakness"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Enter Strengths and Weakness " disabled>{{ $feedback->feedback_strength_weakness }}</textarea>
            </div>
            <div class="mb-5">
                <label for="feedback_comment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Please
                    Comment on how you
                    think
                    to improve on teaching and learning in this subject</label>
                <textarea name="feedback_comment"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Enter Comment " disabled>{{ $feedback->feedback_comment }}</textarea>
            </div>
            <div>
                <label for="feedback_strength_weakness"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teacher's Teaching Strengths and
                    Weakness Analysis</label>
                <div class="grid grid-cols-4 gap-5">
                    <div class=" bg-green-50 bg-opacity-90 shadow-md rounded-md p-2 cursor-pointer">
                        <h1 for="feedback_strength_weakness" class="text-center font-bold">Positive</h1>
                        <p class="text-center">{{ $feedback->feedback_strength_weakness_pos }}</p>
                    </div>
                    <div class=" bg-yellow-50 bg-opacity-90 shadow-md rounded-md p-2 cursor-pointer">
                        <h1 for="feedback_strength_weakness" class="text-center font-bold">Neural</h1>
                        <p class="text-center">{{ $feedback->feedback_strength_weakness_neu }}</p>
                    </div>
                    <div class=" bg-red-50 bg-opacity-90 shadow-md rounded-md p-2 cursor-pointer">
                        <h1 for="feedback_strength_weakness" class="text-center font-bold">Negative</h1>
                        <p class="text-center">{{ $feedback->feedback_strength_weakness_neg }}</p>
                    </div>
                    <div class=" bg-blue-50 bg-opacity-90 shadow-md rounded-md p-2 cursor-pointer">
                        <h1 for="feedback_strength_weakness" class="text-center font-bold">Compound</h1>
                        <p class="text-center">{{ $feedback->feedback_strength_weakness_compound }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <label for="feedback_comment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">To
                    improve on teaching and learning in this subject Analysis</label>
                <div class="grid grid-cols-4 gap-5">
                    <div class=" bg-green-50 bg-opacity-90 shadow-md rounded-md p-2 cursor-pointer">
                        <h1 for="feedback_comment" class="text-center font-bold">Positive</h1>
                        <p class="text-center">{{ $feedback->feedback_comment_pos }}</p>
                    </div>
                    <div class=" bg-yellow-50 bg-opacity-90 shadow-md rounded-md p-2 cursor-pointer">
                        <h1 for="feedback_comment" class="text-center font-bold">Neural</h1>
                        <p class="text-center">{{ $feedback->feedback_comment_neu }}</p>
                    </div>
                    <div class=" bg-red-50 bg-opacity-90 shadow-md rounded-md p-2 cursor-pointer">
                        <h1 for="feedback_comment" class="text-center font-bold">Negative</h1>
                        <p class="text-center">{{ $feedback->feedback_comment_neg }}</p>
                    </div>
                    <div class=" bg-blue-50 bg-opacity-90 shadow-md rounded-md p-2 cursor-pointer">
                        <h1 for="feedback_comment" class="text-center font-bold">Compound</h1>
                        <p class="text-center">{{ $feedback->feedback_comment_compound }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
