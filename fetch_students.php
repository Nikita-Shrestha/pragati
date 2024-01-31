<?php
if (isset($_GET['semester'])) {
    $semester = $_GET['semester'];
    
    // Establish a database connection (you may need to update credentials)
    $conn = new mysqli("localhost", "root", "", "pragati");

    if ($conn->connect_errno != 0) {
        die("Connection failed");
    }

    // Query to fetch student names based on the selected semester
    $sql = "SELECT id, name FROM sregister WHERE sem = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $semester);

    $students = array();

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    } else {
        // Handle any errors here
        $students = array();
    }

    // Close the database connection
    $conn->close();

    // Encode the data as JSON and send it to the client
    echo json_encode($students);
} else {
    // Handle invalid or missing semester parameter
    echo json_encode(array());
}
?>
