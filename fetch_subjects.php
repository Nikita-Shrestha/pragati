<?php
if (isset($_GET['semester'])) {
    $semester = $_GET['semester'];

    $conn = new mysqli("localhost", "root", "", "pragati");
    if ($conn->connect_errno != 0) {
        die("Connection failed");
    }

    // Modify this query to fetch subjects based on the selected semester
    $sql = "SELECT id, sname FROM subject WHERE sem_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $semester);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $subjects = array();

        while ($row = $result->fetch_assoc()) {
            $subjects[] = $row;
        }

        echo json_encode($subjects);
    } else {
        echo "Error fetching subjects";
    }

    $conn->close();
}
?>
