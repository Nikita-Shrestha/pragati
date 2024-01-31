<?php

// Check if the required parameters are set in the URL
if (isset($_GET['studentId']) && isset($_GET['semester'])) {
    $studentId = $_GET['studentId'];
    $semester = $_GET['semester'];

    // Perform database connection
    $conn = new mysqli("localhost", "root", "", "pragati");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Initialize an associative array to store marks for each subject and exam
    $subjectMarks = [];

    // Fetch subject-wise marks from the database for the selected student ID and semester
    $sqlMarks = "SELECT subject, exam, marks FROM marks WHERE student_id = $studentId AND sem = '$semester'";
    $resultMarks = $conn->query($sqlMarks);

    // Check for SQL errors
    if (!$resultMarks) {
        echo "Error in SQL query: " . $conn->error;
        exit();
    }

    // Initialize an associative array to store marks for each subject and exam
    $subjectMarks = [];

    while ($rowMarks = $resultMarks->fetch_assoc()) {
        $exam = $rowMarks['exam'];
        $subject = $rowMarks['subject'];
        $marks = $rowMarks['marks'];

        // Use a unique key for each subject
        $key = $subject;

        // Assign marks to the associative array based on the exam type
        $subjectMarks[$key][$exam] = $marks;
    }

    // Fetch GPAs from the record table for the selected student ID and semester
    $sqlGPAs = "SELECT exam, gpa FROM record WHERE student_id = $studentId AND sem = '$semester'";
    $resultGPAs = $conn->query($sqlGPAs);

    // Initialize arrays to store GPAs for each exam
    $examGPAs = [
        'Mid term' => '',
        'Preboard' => '',
        'Board' => ''
    ];

    while ($rowGPA = $resultGPAs->fetch_assoc()) {
        $exam = $rowGPA['exam'];
        $gpa = $rowGPA['gpa'];

        // Assign GPAs to the associative array based on the exam type
        $examGPAs[$exam] = $gpa;
    }

    // Close the result sets
    $resultMarks->close();
    $resultGPAs->close();

  
} else {
    echo "Error: Missing parameters.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   
<!-- Marksheet display section -->
<div class="mark-display" id="markDisplay">
      <table>
            <thead>
            <tr>
                <th>Subject</th>
                <th>Midterm</th>
                <th>Preboard</th>
                <th>Board</th>
            </tr>
            </thead>
            <tbody>
                    <?php
                     
// Fetch GPAs from the record table for the selected student ID and semester
$sqlGPAs = "SELECT exam, gpa FROM record WHERE student_id = $studentId AND sem = '$semester'";
$resultGPAs = $conn->query($sqlGPAs);

// Initialize arrays to store GPAs for each exam
$examGPAs = [
    'Mid term' => '',
    'Preboard' => '',
    'Board' => ''
];

while ($row = $resultGPAs->fetch_assoc()) {
    $exam = $row['exam'];
    $gpa = $row['gpa'];

    // Assign GPAs to the associative array based on the exam type
    $examGPAs[$exam] = $gpa;
}
            // Initialize arrays to store marks for each exam
$midtermMarks = [];
$preboardMarks = [];
$boardMarks = [];

// Get a list of all unique subjects
$allSubjects = array_keys($subjectMarks);


// Display subjects and marks below each exam column
foreach ($allSubjects as $subject) {

    echo "<tr>";
    echo "<td>{$subject}</td>";
    echo "<td>" . ($subjectMarks[$subject]['Mid term'] ?? '') . "</td>";
    echo "<td>" . ($subjectMarks[$subject]['Preboard'] ?? '') . "</td>";
    echo "<td>" . ($subjectMarks[$subject]['Board'] ?? '') . "</td>";
    echo "</tr>";
}



// Calculate the sum of marks for each exam
$midtermTotal = array_sum(array_column($subjectMarks, 'Mid term'));
$preboardTotal = array_sum(array_column($subjectMarks, 'Preboard'));
$boardTotal = array_sum(array_column($subjectMarks, 'Board'));

// Display total marks row
echo "<tr class='total'>";
echo "<td>Total Marks</td>";
echo "<td>{$midtermTotal}</td>";
echo "<td>{$preboardTotal}</td>";
echo "<td>{$boardTotal}</td>";
echo "</tr>";
// Display GPA row
echo "<tr class='gpa'>";
echo "<td>GPA</td>";
echo "<td>" . ($examGPAs['Mid term'] ?? 'N/A') . "</td>";
echo "<td>" . ($examGPAs['Preboard'] ?? 'N/A') . "</td>";
echo "<td>" . ($examGPAs['Board'] ?? 'N/A') . "</td>";
echo "</tr>";
       
?>
                </tbody>
 
        </table>
    </div>

<?php
// Function to analyze performance trends for Midterm, Preboard, and Board
function analyzeMidtermPreboardAndBoardTrend($studentId, $semester, $conn) {
    // Fetch total marks from the database for Midterm, Preboard, and Board
    $sqlMarks = "SELECT exam, total 
             FROM record 
             WHERE student_id = $studentId
             AND exam IN ('Mid term', 'Preboard', 'Board')
             AND sem = '$semester'";
    $resultMarks = $conn->query($sqlMarks);

    // Initialize arrays to store total marks for each exam
    $examTotalMarks = [
        'Mid term' => null,
        'Preboard' => null,
        'Board' => null,
    ];

    // Populate arrays with total marks for Midterm, Preboard, and Board
    while ($row = $resultMarks->fetch_assoc()) {
        $examTotalMarks[$row['exam']] = $row['total'];
    }

    // Analyze trends based on total marks
    $trendAnalysis = [];
    $exams = array_keys($examTotalMarks);

    // Loop through exams to analyze trends
    for ($i = 0; $i < count($exams); $i++) {
        $currentExam = $exams[$i];
        $trend = "N/A";

        // Skip the first exam as there is no previous exam to compare
        if ($i > 0) {
            $previousExam = $exams[$i - 1];

            // Compare total marks between the current and previous exams
            $currentTotalMarks = $examTotalMarks[$currentExam];
            $previousTotalMarks = $examTotalMarks[$previousExam];

            // Analyze trend based on total marks
            if ($currentTotalMarks !== null && $previousTotalMarks !== null) {
                if ($currentTotalMarks > $previousTotalMarks) {
                    $trend = "Improving";
                } elseif ($currentTotalMarks < $previousTotalMarks) {
                    $trend = "Declining";
                } else {
                    $trend = "Stable";
                }
            }
        }

        // Store trend analysis for the current exam
        $trendAnalysis[$currentExam] = $trend;
    }

    return $trendAnalysis;
}

// Display trend analysis results for Midterm, Preboard, and Board only if there is data
$trends = analyzeMidtermPreboardAndBoardTrend($studentId, $semester, $conn);

// Check if there is data available before displaying the trend analysis table
if (count(array_filter($trends)) > 0) {
    echo "<h2>Total Marks Trend Analysis</h2>";
    echo "<table>";
    echo "<thead><tr><th>Exam</th><th>Trend</th></tr></thead>";
    echo "<tbody>";

    // Display trend analysis for Midterm, Preboard, and Board
    foreach ($trends as $exam => $trend) {
        echo "<tr>";
        echo "<td>$exam</td>";
        echo "<td>$trend</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    
    // Suggestions based on trends
    $lastTrend = end($trends);

    switch ($lastTrend) {
        case 'Improving':
            echo "Excellent performance! Keep up the good work.";
            break;

        case 'Declining':
            echo "Consider spending more time studying for better results.";
            break;

        case 'Stable':
            echo "Keep working hard to maintain your performance.";
            break;

        default:
            break;
    }
}
?>

  <!-- Download button -->
  <a href="download_marksheet.php?report_view=<?php echo $studentId; ?>&semester=<?php echo $semester; ?>" download="marksheet.html">
    <button class="download-button">Download Marksheet</button>
</a>
</div>

 
</body>
</html>

