<?php
session_start();
include("studentdashboard.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Marksheet</title>
    <style>
     body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        color: #333;
        text-align: left;
    }

     .marksheet {
       width:70%;
        margin: 20px auto;
        padding: 30px; /* Adjust padding for better spacing */
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
      max-height: 700px; /* Set a maximum height for the table container */
        overflow-y: auto; /* Add a vertical scrollbar when the content overflows */
     }
    h2 {
        color: #333;
        font-size: 24px;
        margin-bottom: 20px;
    }

    p {
        font-size: 16px;
        margin-bottom: 10px;
    }

    strong {
        font-weight: bold;
    }
  .semester-buttons {
            margin-top: 20px;
        }

        .semester-button {
            padding: 10px 20px;
            font-size: 16px;
            margin-right: 10px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .semester-button:hover {
            background-color: #ddd;
        }
    .text-danger {
        color: #dc3545;
    }
     .mark-display {
            margin-top: 30px;
          
        }
        table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    .total, .gpa {
        font-weight: bold;
    }
/* Style for the download button */
        .download-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            background-color: #4CAF50; /* Green background color */
            color: white; /* White text color */
            border: 1px solid #4CAF50; /* Green border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer;
        }

        /* Hover effect for the download button */
        .download-button:hover {
            background-color: white;
            color: #4CAF50;
        }

</style>
</head>
<body>

<div class="marksheet">
    <?php
    // Check if the session variable is set
    if (isset($_SESSION['user'])) {
        $row = $_SESSION['user'];
        $studentId = $row['id'];
        $studentName = $row['name'];
        $semester = $row['sem'];
        $batch = $row['batch'];

        // Check if the studentId is a valid number
        if (!is_numeric($studentId)) {
            echo "<p class='text-danger'>Error: Invalid student ID.</p>";
            exit();
        }

        $conn = new mysqli("localhost", "root", "", "pragati");

        // Check the database connection
        if ($conn->connect_error) {
            echo "<p class='text-danger'>Error: Connection failed - " . $conn->connect_error . "</p>";
            exit();
        }

        // Fetch student information from the database
        $sqlStudentInfo = "SELECT name, id, batch FROM sregister WHERE id = $studentId";
        $resultStudentInfo = $conn->query($sqlStudentInfo);

        // Check if the query was successful
        if (!$resultStudentInfo) {
            echo "<p class='text-danger'>Error: Unable to fetch student information - " . $conn->error . "</p>";
            exit();
        }

        // Check if student information is found
        if ($resultStudentInfo->num_rows > 0) {
            $studentInfo = $resultStudentInfo->fetch_assoc();

            // Display student information
            echo "<h2>Student Marksheet</h2>";
            echo "<p><strong>Name:</strong> $studentName</p>";
            echo "<p><strong>Student ID:</strong> $studentId</p>";
            echo "<p><strong>Semester & Batch:</strong> $semester - " . $studentInfo['batch'] . "</p>";
        } else {
            echo "<p class='text-danger'>Error: Student not found.</p>";
        }
  // Fetch available semesters from the marks table
  $sqlSemesters = "SELECT DISTINCT sem FROM marks WHERE student_id = $studentId";
  $resultSemesters = $conn->query($sqlSemesters);

  if (!$resultSemesters) {
      echo "<p class='text-danger'>Error: Unable to fetch available semesters - " . $conn->error . "</p>";
      exit();
  }

  if ($resultSemesters->num_rows > 0) {
      $availableSemesters = $resultSemesters->fetch_all(MYSQLI_ASSOC);
      echo "<div class='semester-buttons'>";
      foreach ($availableSemesters as $sem) {
          echo "<button class='semester-button' onclick='selectSemester(\"$sem[sem]\")'>Semester $sem[sem]</button>";
      }
      echo "</div>";
  } else {
      echo "<p class='text-danger'>No semesters found for this student.</p>";
      $conn->close();
      exit();
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

}
    ?>
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

</div>
<script>
    function selectSemester(selectedSemester) {
        console.log('Selected Semester: ' + selectedSemester);
        displayMarksheet(<?php echo $studentId; ?>, selectedSemester);
    }

    function displayMarksheet(studentId, semester) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("markDisplay").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "getMarksheet.php?studentId=" + studentId + "&semester=" + semester, true);
        xmlhttp.send();
    }
</script>

</body>
</html>
