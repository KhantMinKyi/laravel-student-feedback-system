<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class TeacherCourseChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct($yearName, $data)
    {
        parent::__construct();

        // Extract course names and average feedback percentages
        $courseNames = array_map(function ($item) {
            return $item['course_name'];
        }, $data);

        $averageFeedbacks = array_map(function ($item) {
            return $item['average_feedback_percentage'];
        }, $data);

        // Set chart options and data
        $this->labels($courseNames);
        $this->dataset($yearName, 'bar', $averageFeedbacks);
    }
}
