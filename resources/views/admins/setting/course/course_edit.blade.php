@extends('admins.layout')

@section('content')
    <div class="grid grid-cols-3">
        <div>
        </div>


        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <form class="max-w-sm mx-auto" action="{{ route('course.update', ['course' => $course->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-5">
                    <label for="course_name"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Course</label>
                    <input type="text" name="course_name" value="{{ $course->course_name }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter Course Name" required />
                </div>
                <div class="mb-5">
                    <label for="year_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Year</label>
                    <select id="year_id" name="year_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        @foreach ($years as $year)
                            <option value="{{ $year->id }}" @if ($year->id == $course->year->id) selected @endif>
                                {{ $year->year_name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="semester"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Semester</label>
                    <select id="semester" name="semester"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                        <option value="1" {{ $course->semester == 1 ? 'selected' : '' }}>First</option>
                        <option value="2" {{ $course->semester == 2 ? 'selected' : '' }}>Second</option>
                    </select>
                </div>
                <button type="submit"
                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Update</button>
            </form>

        </div>

    </div>
@endsection
