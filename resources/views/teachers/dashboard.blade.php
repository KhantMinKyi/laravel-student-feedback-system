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
    <div class="grid grid-cols-1">
        @foreach ($yearlyData as $yearData)
            <div class="year-section m-4">
                <h2 class="text-md font-semibold text-center">Chart for {{ $yearData['name'] }}</h2>
                <div class="year-charts flex flex-wrap">
                    @foreach ($yearData['data'] as $yearId => $dataGroup)
                        <div class="year-id-section w-1/2 px-2">
                            <h3 class="text-sm font-semibold text-center">{{ $yearId }}</h3>
                            <canvas id="chart-{{ $yearData['name'] }}-{{ $yearId }}"></canvas>
                        </div>
                    @endforeach
                </div>
                <hr class="my-4 border-2 rounded border-red-300">
            </div>
        @endforeach
    </div>
    <div class="flex justify-center mb-4 rounded-xl bg-red-50 dark:bg-gray-800 ">
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

    {{-- <div class="grid grid-cols-2 gap-4 mb-4">
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
    </div> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($yearlyData as $yearData)
                var yearName = "{{ $yearData['name'] }}";
                var yearDataGroups = {!! json_encode($yearData['data']) !!};

                for (var yearId in yearDataGroups) {
                    if (yearDataGroups.hasOwnProperty(yearId)) {
                        var dataGroup = yearDataGroups[yearId];
                        var courseNames = dataGroup.map(item => item.course_name);
                        var feedbackPercentages = dataGroup.map(item => item.average_feedback_percentage);
                        var stronglyAgreePercentages = dataGroup.map(item => item
                            .average_strongly_agree_point_percentage);
                        var agreePercentages = dataGroup.map(item => item.average_agree_point_percentage);
                        var neutralPercentages = dataGroup.map(item => item.average_neutral_point_percentage);
                        var disagreePercentages = dataGroup.map(item => item.average_disagree_point_percentage);
                        var stronglyDisagreePercentages = dataGroup.map(item => item
                            .average_strongly_disagree_point_percentage);
                        var feedbackPercentagesComment = dataGroup.map(item => item
                            .average_feedback_percentage_comment);

                        // Create a unique ID for each chart
                        var chartId = 'chart-{{ $yearData['name'] }}-' + yearId;

                        // Create canvas element for the chart
                        var canvas = document.getElementById(chartId);
                        var ctx = canvas.getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: courseNames,
                                datasets: [{
                                        label: 'Average Feedback Percentage',
                                        data: feedbackPercentages,
                                        backgroundColor: 'rgba(36, 14, 254, 0.2)',
                                        borderColor: 'rgba(36, 14, 254, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Strongly Agree',
                                        data: stronglyAgreePercentages,
                                        backgroundColor: 'rgba(40, 217, 38, 0.2)',
                                        borderColor: 'rgba(40, 217, 38, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Agree',
                                        data: agreePercentages,
                                        backgroundColor: 'rgba(38, 217, 198, 0.2)',
                                        borderColor: 'rgba(38, 217, 198, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Neutral',
                                        data: neutralPercentages,
                                        backgroundColor: 'rgba(235, 241, 44, 0.2)',
                                        borderColor: 'rgba(235, 241, 44, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Disagree',
                                        data: disagreePercentages,
                                        backgroundColor: 'rgba(241, 164, 44, 0.2)',
                                        borderColor: 'rgba(241, 164, 44, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Strongly Disagree',
                                        data: stronglyDisagreePercentages,
                                        backgroundColor: 'rgba(241, 44, 44, 0.2)',
                                        borderColor: 'rgba(241, 44, 44, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Comment',
                                        data: feedbackPercentagesComment,
                                        backgroundColor: 'rgba(182, 182, 182, 0.2)',
                                        borderColor: 'rgba(182, 182, 182, 1)',
                                        borderWidth: 1
                                    },
                                ]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    title: {
                                        display: true,
                                        text: yearName + ' - ' + yearId
                                    }
                                }
                            }
                        });
                    }
                }
            @endforeach
        });
    </script>
@endsection
