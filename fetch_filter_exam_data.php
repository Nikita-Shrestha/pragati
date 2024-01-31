<?php
// Establish a database connection (you may need to update credentials)
$conn = new mysqli("localhost", "root", "", "pragati");

if ($conn->connect_errno != 0) {
    die("Connection failed");
}

// Get the selected subject and exam from POST data
$selectedSubject = strval($_POST['subject']);
$selectedExam = $_POST['exam'];

// Query to fetch data based on selected filters
$sql = "SELECT student_name, marks_o FROM marks WHERE subject = ? AND exam = ?";
$stmt = $conn->prepare($sql);

$stmt->bind_param("si", $selectedSubject, $selectedExam);

$data = array();

if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    // Handle any errors here
    $data = array();
}

// Close the database connection
$conn->close();

// Encode the data as JSON and send it to the client
echo json_encode($data);
?>
