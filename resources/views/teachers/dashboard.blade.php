@extends('teachers.layout')
@section('content')
    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-yellow-100 dark:bg-gray-800 ">
            <div class="text-lg font-bold">Teacher Count</div>
            <div class="text-xl font-bold">{{ $teacher_count }}</div>
        </div>
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-green-100 dark:bg-gray-800">
            <div class="text-lg font-bold">Student Count</div>
            <div class="text-xl font-bold">{{ $student_count }}</div>
        </div>
        <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-blue-100 dark:bg-gray-800">
            <div class="text-lg font-bold">Today</div>
            <div class="text-xl font-bold">{{ date('d-m-Y', strToTime(Carbon\Carbon::now())) }}</div>
        </div>
    </div>
    {{-- Charts --}}
    <div style="width: 80%;" class="grid grid-cols-2">
        @foreach ($yearlyData as $yearData)
            <div class=" m-4">
                <h2 class="text-md font-semibold text-center">Chart for {{ $yearData['name'] }}</h2>
                <canvas id="chart-{{ $yearData['name'] }}"></canvas>
            </div>
        @endforeach
    </div>
    <div class="flex justify-center h-48 mb-4 rounded-xl bg-red-50 dark:bg-gray-800 ">
        <table class="w-full text-sm text-left rtl:text-right rounded-lg text-gray-500 dark:text-gray-400">
            <thead
                class="text-md text-gray-700 uppercase bg-red-100 dark:bg-gray-700 dark:text-gray-400 table-header-group">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Teacher Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Subject
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Teaching Year
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Year
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($teacher_courses->teacher_courses as $no => $teacher_course)
                    @isset($teacher_course)
                        <tr
                            class=" border-b border-red-100 dark:bg-red-800 dark:border-red-700 hover:bg-red-100 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">{{ $teacher_courses->teacher->name }}</td>
                            <td class="px-6 py-4">{{ $teacher_course->courses->course_name }} ( Semester -
                                {{ $teacher_course->courses->semester }} )</td>
                            <td class="px-6 py-4">{{ $teacher_course->teaching_year }} -
                                {{ $teacher_course->teaching_year_second_semester }}</td>
                            <td class="px-6 py-4">{{ $teacher_course->courses->year->year_name }}</td>
                        </tr>
                    @endisset
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="flex items-center justify-center rounded bg-gray-50 h-28 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded bg-gray-50 h-28 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded bg-gray-50 h-28 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded bg-gray-50 h-28 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
    </div>
    <div class="flex items-center justify-center h-48 mb-4 rounded bg-gray-50 dark:bg-gray-800">
        <p class="text-2xl text-gray-400 dark:text-gray-500">
            <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 18 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 1v16M1 9h16" />
            </svg>
        </p>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div class="flex items-center justify-center rounded bg-gray-50 h-28 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded bg-gray-50 h-28 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded bg-gray-50 h-28 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center rounded bg-gray-50 h-28 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($yearlyData as $yearData)
                var ctx = document.getElementById('chart-{{ $yearData['name'] }}').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_column($yearData['data'], 'course_name')) !!},
                        datasets: [{
                            label: 'Average Feedback Percentage for {{ $yearData['name'] }}',
                            data: {!! json_encode(array_column($yearData['data'], 'average_feedback_percentage')) !!},
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            @endforeach
        });
    </script>
@endsection
