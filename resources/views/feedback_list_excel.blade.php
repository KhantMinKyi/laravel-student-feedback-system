@php
    use App\Models\TeacherCourse;
    use App\Models\Course;
    use App\Models\StudentYear;
    use App\Models\Feedback;
@endphp
<h1 class="text-sm my-2 mt-4 font-bold text-green-800">( {{ $final_data['name'] }} )
</h1>
<table class=" table-auto w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border-b-2">
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
        @foreach ($final_data['data'] as $number => $subject)
            <tr
                class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <td class="px-2 py-2">{{ $number + 1 }}</td>
                <td class="px-2 py-2">{{ $subject['course_name'] }}</td>
                <td class="px-2 py-2">
                    @php
                        $years = explode('-', $final_data['name']);
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
                            $feedback_questions = explode(',', $feedback_detail->feedback_questions);
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
