@extends('teachers.layout')
@section('content')
    @php
        use App\Models\TeacherCourse;
        use App\Models\Course;
        use App\Models\StudentYear;
        use App\Models\Feedback;
    @endphp
    {{-- Table --}}
    <div>
        @foreach ($yearlyData as $yearData)
            <div>
                <h2 class="text-md font-semibold text-center">Table for {{ $yearData['name'] }}</h2>
                @foreach ($yearData['data'] as $year => $data)
                    <div class="flex justify-between my-2">
                        <h1 class="text-sm my-2 mt-4 font-bold text-green-800">{{ $year }}( {{ $yearData['name'] }} )
                        </h1>
                        <form action="{{ route('teacher.feedback.export') }}" method="POST">
                            @csrf
                            <input type="hidden" name="name" value="{{ $yearData['name'] }}">
                            <input type="hidden" name="data" value="{{ json_encode($data) }}">
                            <button type="submit" class="p-2 bg-blue-400 rounded-md  font-bold cursor-pointer">Export
                                Excel</button>
                        </form>
                    </div>

                    <table
                        class=" table-auto w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border-b-2">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-2 py-2">No</th>
                                <th class="px-2 py-2" style="width: 150px;">Course Title</th>
                                <th class="px-2 py-2">Teacher Name</th>
                                <th class="px-2 py-2">Total Pages</th>
                                <th class="px-2 py-2">Recieved Pages</th>
                                <th class="px-2 py-2">Total Mark</th>
                                <th class="px-2 py-2">Strongly Agree</th>
                                <th class="px-2 py-2">Agree</th>
                                <th class="px-2 py-2">Neutral</th>
                                <th class="px-2 py-2">Disagree</th>
                                <th class="px-2 py-2">Strongly Disagree</th>
                                <th class="px-2 py-2">Strongly Agree %</th>
                                <th class="px-2 py-2">Agree %</th>
                                <th class="px-2 py-2">Neutral %</th>
                                <th class="px-2 py-2">Disagree %</th>
                                <th class="px-2 py-2">Strongly Disagree %</th>
                                <th class="px-2 py-2">Overall %</th>
                                <th class="px-2 py-2">(Strongly Agree + Agree) %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $number => $subject)
                                <tr
                                    class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                    <td class="px-2 py-2">{{ $number + 1 }}</td>
                                    <td class="px-2 py-2">{{ $subject['course_name'] }}</td>
                                    <td class="px-2 py-2">
                                        @php
                                            $years = explode('-', $yearData['name']);
                                            $teacher = TeacherCourse::where('course_id', $subject['course_id'])
                                                ->where('teaching_year', $years[0])
                                                ->first();
                                            echo $teacher->teacher->name;
                                        @endphp
                                    </td>
                                    <td class="px-2 py-2">
                                        @php
                                            $course = Course::find($subject['course_id']);
                                            $student_count = StudentYear::where('year_id', $course->year_id)
                                                ->where('learning_year', $years[0])
                                                ->get()
                                                ->count();
                                            echo $student_count;
                                        @endphp
                                    </td>
                                    <td class="px-2 py-2">
                                        @php
                                            $feedback_count = Feedback::where('year_id', $course->year_id)
                                                ->where('learning_year', $years[0])
                                                ->where('course_id', $course->id)
                                                ->get()
                                                ->count();
                                            echo $feedback_count;
                                        @endphp
                                    </td>
                                    <td class="px-2 py-2">
                                        @php
                                            $feedback_data = Feedback::where('year_id', $course->year_id)
                                                ->where('learning_year', $years[0])
                                                ->where('course_id', $course->id)
                                                ->get();
                                            $question_marks = 0;
                                            foreach ($feedback_data as $feedback_detail) {
                                                $feedback_questions = explode(
                                                    ',',
                                                    $feedback_detail->feedback_questions,
                                                );
                                                $question_marks += count($feedback_questions) * 5;
                                            }
                                            $total_mark =
                                                $subject['strongly_agree_point'] +
                                                $subject['agree_point'] +
                                                $subject['neutral_point'] +
                                                $subject['disagree_point'] +
                                                $subject['strongly_disagree_point'];
                                            echo $total_mark;
                                        @endphp
                                    </td>
                                    <td class="px-2 py-2">{{ $subject['strongly_agree_point'] }}</td>
                                    <td class="px-2 py-2">{{ $subject['agree_point'] }}</td>
                                    <td class="px-2 py-2">{{ $subject['neutral_point'] }}</td>
                                    <td class="px-2 py-2">{{ $subject['disagree_point'] }}</td>
                                    <td class="px-2 py-2">{{ $subject['strongly_disagree_point'] }}</td>
                                    <td class="px-2 py-2">{{ $subject['average_strongly_agree_point_percentage'] }}</td>
                                    <td class="px-2 py-2">{{ $subject['average_agree_point_percentage'] }}</td>
                                    <td class="px-2 py-2">{{ $subject['average_neutral_point_percentage'] }}</td>
                                    <td class="px-2 py-2">{{ $subject['average_disagree_point_percentage'] }}</td>
                                    <td class="px-2 py-2">{{ $subject['average_strongly_disagree_point_percentage'] }}</td>
                                    <td class="px-2 py-2">
                                        @php
                                            $total_percentage = round(($total_mark * 100) / $question_marks, 2);
                                            echo $total_percentage;
                                        @endphp
                                    </td>
                                    <td class="px-2 py-2">
                                        {{ $subject['average_strongly_agree_point_percentage'] + $subject['average_agree_point_percentage'] }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr class="mb-8">
                @endforeach
            </div>
        @endforeach
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1">
        @foreach ($yearlyData as $yearData)
            <div class="year-section m-4">
                <h2 class="text-md font-semibold text-center">Chart for {{ $yearData['name'] }}</h2>
                <div class="year-charts flex flex-wrap">
                    @foreach ($yearData['data'] as $yearId => $dataGroup)
                        <div class="year-id-section w-5/6 px-2">
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




    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($yearlyData as $yearData)
                var courseNames = {!! json_encode(array_column($yearData['data'], 'course_name')) !!};
                var feedbackPercentages = {!! json_encode(array_column($yearData['data'], 'average_feedback_percentage')) !!};
                var feedbackPercentagesComment = {!! json_encode(array_column($yearData['data'], 'average_feedback_percentage_comment')) !!};
                var ctx = document.getElementById('chart-{{ $yearData['name'] }}').getContext('2d');
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
                        }
                    }
                });
            @endforeach
        });
    </script> --}}
@endsection
