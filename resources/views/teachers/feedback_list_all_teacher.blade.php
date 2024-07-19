@extends('teachers.layout')
@section('content')
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
