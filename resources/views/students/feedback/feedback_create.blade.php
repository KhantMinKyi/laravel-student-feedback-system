@extends('students.layout')
@section('content')
    @foreach ($student_year->year->courses as $index => $course)
        <div class="flex justify-center my-4">
            <div
                class="min-w-max p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <div>
                    <h5 class="text-xl my-4 text-center font-bold">Teacher Feedback Form</h5>
                    <p class="text-lg my-2 font-semibold">Teacher Name : {{ $course->teacher->name }}</p>
                    <p class="text-lg my-2 font-semibold">Course : {{ $course->course_name }}</p>
                    <p class="text-lg mt-2 font-semibold">Student Name : {{ Auth::user()->name }}</p>
                    <span class="text-sm text-gray-400">Your name will not appear on paper !</span>
                    <hr class="my-4">
                </div>
                <form class="min-w-max mx-auto" action="{{ route('feedback.store') }}" method="POST">
                    @csrf
                    @foreach ($questions as $key => $question)
                        <input type="hidden" name="year_id" value="{{ $student_year->year_id }}">
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <input type="hidden" name="teacher_id" value="{{ $course->teacher->id }}">
                        <input type="hidden" name="student_id" value="{{ $student_year->student_id }}">
                        <input type="hidden" name="learning_year" value="{{ $student_year->learning_year }}">
                        <input type="hidden" name="learning_year_second_semester"
                            value="{{ $student_year->learning_year_second_semester }}">
                        <input type="hidden" name="feedback_template_id"
                            value="{{ $feedback_template->feedback_template_id }}">
                        <div class="mb-5">
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $question }}</label>
                            <div class="grid grid-cols-5">
                                <div class="flex flex-col justify-center items-center">
                                    <input type="radio" name="question_{{ $key }}" required value="very_good" />
                                    <span>Strongly Agree</span>
                                </div>
                                <div class="flex flex-col justify-center items-center">
                                    <input type="radio" name="question_{{ $key }}" required value="good" />
                                    <span>Agree</span>
                                </div>
                                <div class="flex flex-col justify-center items-center">
                                    <input type="radio" name="question_{{ $key }}" required value="normal" />
                                    <span>Neutral</span>
                                </div>
                                <div class="flex flex-col justify-center items-center">
                                    <input type="radio" name="question_{{ $key }}" required value="not_bad" />
                                    <span>Disagree</span>
                                </div>
                                <div class="flex flex-col justify-center items-center">
                                    <input type="radio" name="question_{{ $key }}" required value="bad" />
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
                            placeholder="Enter Strengths and Weakness "></textarea>
                    </div>
                    <div class="mb-5">
                        <label for="feedback_comment"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Please Comment on how you
                            think
                            to improve on teaching and learning in this subject</label>
                        <textarea name="feedback_comment"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Enter Comment "></textarea>
                    </div>
                    <div>

                    </div>
                    <button type="submit"
                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Create</button>
                </form>

            </div>
        </div>
    @endforeach
@endsection
