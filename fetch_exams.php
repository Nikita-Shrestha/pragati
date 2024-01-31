<?php
    // Establish a database connection (you may need to update credentials)
    $conn = new mysqli("localhost", "root", "", "pragati");

    if ($conn->connect_errno != 0) {
        die("Connection failed");
    }

    // Query to fetch exams based on the selected semester
    $sql = "SELECT id, exam FROM exam";
    $stmt = $conn->prepare($sql);

    $exams = array();

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $exams[] = $row;
        }
    } else {
        // Handle any errors here
        $exams = array();
    }

    // Close the database connection
    $conn->close();

    // Encode the data as JSON and send it to the client
    echo json_encode($exams);
?>
