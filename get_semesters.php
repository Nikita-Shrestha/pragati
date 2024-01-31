<?php
// get_semesters.php

$studentId = $_GET['studentId'];

// Replace the following with your database connection logic
$conn = new mysqli("localhost", "root", "", "pragati");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch semesters from the record and marks tables
$semesters = [];

// Fetch semesters from the record table
$recordQuery = "SELECT DISTINCT sem FROM record WHERE student_id = $studentId";
$recordResult = $conn->query($recordQuery);

if ($recordResult->num_rows > 0) {
    while ($row = $recordResult->fetch_assoc()) {
        $semesters[] = $row['sem'];
    }
}

// Fetch semesters from the marks table
$marksQuery = "SELECT DISTINCT sem FROM marks WHERE student_id = $studentId";
$marksResult = $conn->query($marksQuery);

if ($marksResult->num_rows > 0) {
    while ($row = $marksResult->fetch_assoc()) {
        $semesters[] = $row['sem'];
    }
}

// Close the database connection
$conn->close();

// Remove duplicates and sort the array
$semesters = array_unique($semesters);
sort($semesters);

header('Content-Type: application/json');
echo json_encode(['semesters' => $semesters]);
?>
