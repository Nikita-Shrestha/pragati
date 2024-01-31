<?php
session_start();
include('dashboard.php');
$conn = new mysqli("localhost", "root", "", "pragati");

if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['sem']) && isset($_POST['student_name']) && isset($_POST['exam'])&& isset($_POST['student_id'])) {
        $exam = $_POST['exam'];
        $semester = $_POST['sem'];
        $id=$_POST['student_id'];
        $studentName = $_POST['student_name'];
        $totalMarks=$_POST['total'];
        $gpa=$_POST['gpa'];
        $passOrFail=$_POST['result'];

        // Retrieve the subject names
        $subjectNames = json_decode($_POST['subject']);
        
        // Define an array to store marks
        $marks = [];

        // Retrieve individual marks for each subject
        for ($i = 1; $i <= count($subjectNames); $i++) {
            $subjectKey = "subject_" . $i;
            $marks[$i] = $_POST[$subjectKey];
        }

        // Insert or update individual marks into the 'marks' table
        for ($i = 1; $i <= count($subjectNames); $i++) {
            $subjectName = $subjectNames[$i - 1]; // Subject name for the current mark
            $mark = $marks[$i]; // Mark for the current subject

            // Check if marks already exist for this subject and student
            $existingMarksQuery = "SELECT id FROM marks WHERE sem = $semester AND exam = '$exam' AND student_name = '$studentName' AND subject = '$subjectName'";
            $existingMarksResult = $conn->query($existingMarksQuery);

            if ($existingMarksResult->num_rows > 0) {
                // If marks exist, update them
                $updateMarksQuery = "UPDATE marks SET student_id=?,marks = ? WHERE sem = ? AND exam = ? AND student_name = ? AND subject = ?";
                $stmt = $conn->prepare($updateMarksQuery);
                $stmt->bind_param("isisss", $id,$mark, $semester, $exam, $studentName, $subjectName);
                $stmt->execute();
                $stmt->close();
            } else {
                // If marks don't exist, insert them
                $insertMarksQuery = "INSERT INTO marks (sem,student_id, exam, subject, student_name, marks) VALUES (?,?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertMarksQuery);
                $stmt->bind_param("iissss", $semester,$id, $exam, $subjectName, $studentName, $mark);
                $stmt->execute();
                $stmt->close();
            }
        }

       // Calculate total, average, and pass/fail here


// Check if marks already exist for this subject and student
$existingRecordQuery = "SELECT id FROM record WHERE sem = ? AND exam = ? AND student_name = ?";
$stmt = $conn->prepare($existingRecordQuery);
$stmt->bind_param("iss", $semester, $exam, $studentName);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
    // If the record exists, update it
    $updateRecordQuery = "UPDATE record SET student_id=?, total = ?, gpa = ?, result = ? WHERE sem = ? AND exam = ? AND student_name = ?";
    $stmt = $conn->prepare($updateRecordQuery);
    $stmt->bind_param("dddssss", $id,$totalMarks, $gpa, $passOrFail, $semester, $exam, $studentName);
    $stmt->execute();
    $stmt->close();
} else {
    // If the record doesn't exist, insert a new record
    $insertRecordQuery = "INSERT INTO record (sem,student_id, student_name, exam, total, gpa, result) VALUES (?, ?,?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertRecordQuery);
    $stmt->bind_param("sissdss", $semester,$id, $studentName, $exam, $totalMarks, $gpa, $passOrFail);
    $stmt->execute();
    $stmt->close();
}

        // Redirect to a success page or wherever you need
        
    } else {
        echo 'Semester, student name, or exam not provided.';
    }
}
?>
