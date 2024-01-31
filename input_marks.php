<?php
session_start();
// Include database connection and other necessary files
include('dashboard.php');

// Establish a database connection
$conn = new mysqli("localhost", "root", "", "pragati");

// Check if the database connection was successful
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

// Check if the semester, student name, and exam are set in the URL
if (isset($_GET['sem']) && isset($_GET['name']) && isset($_GET['exam']) && isset($_GET['id'])) {
    $semester = $_GET['sem'];
    $exam = $_GET['exam'];
    $id = $_GET['id'];
    $studentName = urldecode($_GET['name']);
    
    // Query to retrieve subject names for the specified semester
    $subjectQuery = "SELECT sname FROM subject WHERE sem_id = $semester";
    $subjectResult = $conn->query($subjectQuery);
    
    // Check if there are subjects available for this semester
    if ($subjectResult->num_rows > 0) {
        $subjectNames = array();
        while ($subjectRow = $subjectResult->fetch_assoc()) {
            $subjectNames[] = $subjectRow['sname'];
        }
    } else {
        // No subjects found for the specified semester
        echo '<p>No subjects found for ' . $studentName . ' in Semester ' . $semester . '.</p>';
    }

    // Modify the SQL query to retrieve marks for a specific student, semester, and exam
    $marksQuery = "SELECT subject, marks FROM marks WHERE sem = $semester AND student_name = '$studentName' AND exam = '$exam'";
    $marksResult = $conn->query($marksQuery);

    // Create an associative array to store marks for each subject
    $studentMarks = array();
    if ($marksResult->num_rows > 0) {
        while ($marksRow = $marksResult->fetch_assoc()) {
            $subjectKey = str_replace(' ', '_', strtolower($marksRow['subject']));
            $studentMarks[$subjectKey] = $marksRow['marks'];
        }
    }
} else {
    // Semester, student name, or exam not provided in the URL
    echo 'Semester, student name, or exam not provided.';
}
?>



<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            border-collapse: collapse;
            margin: 0 auto;
            background-color: white;
            border: 1px solid #ccc;
            
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
        }

        table caption {
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        table th {
            background-color: #eee;
        }

        input[type="text"],input[type="number"] {
            width: 100%;
            padding: 5px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
       
    </style>
</head>
<body>
<form name="marksForm" action="store_marks.php" method="post">
    <input type="hidden" name="sem" value="<?php echo $semester; ?>">
    <input type="hidden" name="student_name" value="<?php echo $studentName; ?>">
    <input type="hidden" name="exam" value="<?php echo $exam; ?>">
    <input type="hidden" name="student_id" value="<?php echo $id; ?>">


    <input type="hidden" name="subject" value='<?php echo json_encode($subjectNames); ?>'>
    
    <table border="1" cellspacing="5" cellpadding="5">
        <caption>Input Marks</caption>
        <tr>
            <th rowspan="2">Name</th>
            <th rowspan="2">Exam</th>
        </tr>
        <tr>
            <?php
            // Dynamically generate subject headers based on retrieved subjects
            foreach ($subjectNames as $subjectName) {
                echo '<th>' . $subjectName . '</th>';
            }
            ?>
            <th>Total</th>
            <th>GPA</th>
            <th>Remarks</th>
        </tr>
        <tr>
    <td><input type="text" id="aname" value="<?php echo $studentName; ?>" readonly></td>
    <td><input type="text" value="<?php echo $exam; ?>" readonly></td>
    <?php
    $subjectCount = count($subjectNames);

// Dynamically generate input fields for subject marks
for ($i = 1; $i <= $subjectCount; $i++) {
    $subjectNameWithSpaces = $subjectNames[$i - 1];
    $subjectKey = str_replace(' ', '_', strtolower($subjectNameWithSpaces));
    $markValue = isset($studentMarks[$subjectKey]) ? $studentMarks[$subjectKey] : ''; // Get the pre-filled value

    echo '<td><input type="number" min="0" max="100" name="subject_' . $i . '" value="' . $markValue . '" required></td>';
}
    
    ?>
    <td><input type="text" id="at" name="total" readonly></td> <!-- Total -->
  
   
   <!-- Add a field to display the calculated GPA -->
   <td><input type="text" id="agpa" name="gpa" readonly ></td>



    <td><input type="text" id="ap" name="result" readonly></td> <!-- Pass/Fail -->
</tr>

        <tr>
            <th colspan="<?php echo count($subjectNames) + 5; ?>">
                <input type="submit" value="Add/Update">
            </th>
        </tr>
    </table>
</form>

<script type="text/javascript">
    document.forms["marksForm"].addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the form from submitting

        var total = 0;
        var gradePoints = 0;
        var marks = document.querySelectorAll('input[name^="subject_"]');
        var gradingScale = {
            'A+': 4.0,
            'A': 4.0,
            'A-': 3.7,
            'B+': 3.3,
            'B': 3.0,
            'B-': 2.7,
            'C+': 2.3,
            'C': 2.0,
            'C-': 1.7,
            'D': 1.0,
            'F': 0.0
        };

        var isFail = false; // Initialize the flag to check if the student has failed

marks.forEach(function (mark) {
    var markValue = parseFloat(mark.value) || 0;
    var percentage = (markValue / 100) * 100; // Assuming marks are out of 100

    total += percentage;

    // Convert the percentage to GPA based on the grading scale
    var grade = getGradeFromPercentage(percentage, gradingScale);
    var gpa = gradingScale[grade];
    gradePoints += gpa;

    if (markValue < 40) {
        isFail = true; // If any subject score is less than 40, the student has failed
    }
});

var subjectCount = marks.length;
var gpa = gradePoints / subjectCount; // Calculate GPA

var passOrFail = isFail ? "Fail" : "Pass"; // Set pass/fail based on the flag



        var subjectCount = marks.length;
        var gpa = gradePoints / subjectCount; // Calculate GPA

        
        // Set the calculated values to the appropriate input fields
        document.getElementById('at').value = total.toFixed(2);
        document.getElementById('agpa').value = gpa.toFixed(2); // Display GPA
        document.getElementById('ap').value = passOrFail;

        // Submit the form programmatically using its name
        document.forms["marksForm"].submit();
    });

    function getGradeFromPercentage(percentage, gradingScale) {
        for (var grade in gradingScale) {
            if (percentage >= gradingScale[grade] * 25) {
                return grade;
            }
        }
        return 'F'; // Default to 'F' if no match found
    }
</script>

</body>

</html>
