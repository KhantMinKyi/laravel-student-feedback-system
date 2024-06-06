@extends('admins.layout')
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


    {{-- <div class="flex justify-center h-48 mb-4 rounded-xl bg-red-50 dark:bg-gray-800 ">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Teacher name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Courses
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Year
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($teachers as $teacher)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $teacher['teacher']->name }}
                        </th>

                        <td class="px-6 py-4">
                            @foreach ($teacher['teacher_courses'] as $teacher_course)
                                <b>{{ $teacher_course->courses->course_name }} </b>
                                ({{ $teacher_course->courses->year->year_name }})
                                ( Semester {{ $teacher_course->courses->semester }} )
                                <br>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            @foreach ($teacher['teacher_courses'] as $teacher_course)
                                {{ $teacher_course->teaching_year }} -
                                {{ $teacher_course->teaching_year_second_semester }}
                                <br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div> --}}
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
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Average Feedback Percentage From Comment',
                                        data: feedbackPercentagesComment,
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        borderWidth: 1
                                    }
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
