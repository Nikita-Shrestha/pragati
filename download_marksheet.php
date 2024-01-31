


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
/* Reset some default styles */
body, h1, h2, p, table {
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    color: #333;
}

.marksheet {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.student-info {
    margin-bottom: 20px;
}

h2 {
    color: #333;
    margin-bottom: 10px;
}

p {
    font-size: 16px;
    margin-bottom: 10px;
}

.table-container {
    overflow-x: auto;
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

th, td {
    border: 1px solid #ddd;
    padding: 15px;
    text-align: left;
    font-size: 16px;
}
th:nth-child(4),
td:nth-child(4) {
    border-right: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
}

tbody tr:hover {
    background-color: #f5f5f5;
}

.total {
    font-weight: bold;
    margin-top: 20px;
    font-size: 18px;
}

/* Responsive styles */
@media (max-width: 600px) {
    table {
        font-size: 14px;
    }
}

</style>


</head>
<body>
<?php

if (isset($_GET['report_view']) && isset($_GET['semester'])) {
    $studentId = $_GET['report_view'];
    $semester = $_GET['semester'];



    // Connect to the database and fetch necessary data (similar to your existing code)
    $conn = new mysqli("localhost", "root", "", "pragati");

    // Check the database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

  
// Fetch student information from the database
$sqlStudentInfo = "SELECT name, id, batch, sem FROM sregister WHERE id = $studentId";
$resultStudentInfo = $conn->query($sqlStudentInfo);

// Check if the query was successful
if ($resultStudentInfo === false) {
    echo "Error: Unable to fetch student information.";
    // Exit the script if the query fails
    exit();
}

// Check if student information is found
if ($resultStudentInfo->num_rows > 0) {
    $studentInfo = $resultStudentInfo->fetch_assoc();

    echo "<h2>Student Marksheet</h2>";
    echo "<p><strong>Name:</strong> " . $studentInfo['name'] . "</p>";
    echo "<p><strong>Student ID:</strong> " . $studentInfo['id'] . "</p>";

    // Combine Semester and Batch into a single line
    echo "<p><strong>Semester & Batch:</strong> " . $studentInfo['sem'] . " - " . $studentInfo['batch'] . "</p>";
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
}
}
?>

</div>

<!-- Marksheet Table -->
<div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Midterm</th>
                        <th>Preboard</th>
                        <th>Board</th>
                    </tr>
                </thead>
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
      
        
    </div>
</body>
</html>

    
