@extends('admins.layout')

@section('content')
    <div class="grid grid-cols-3">
        <div>
        </div>


        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <form class="max-w-sm mx-auto" action="{{ route('year.update', ['year' => $year->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-5">
                    <label for="year_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Year</label>
                    <input type="text" name="year_name" value="{{ $year->year_name }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter Year " required />
                </div>
                <div class="mb-5">
                    <label for="semester"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Semester</label>
                    <select id="semester" name="semester"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                        <option value="1" {{ $year->semester == 1 ? 'selected' : '' }}>First</option>
                        <option value="2" {{ $year->semester == 2 ? 'selected' : '' }}>Second</option>
                    </select>
                </div>
                <button type="submit"
                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Create</button>
            </form>

        </div>

    </div>
@endsection
