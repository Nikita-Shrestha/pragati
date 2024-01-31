<?php
if (isset($_GET['semester'])) {
    $semester = $_GET['semester'];

    // Check if the semester is provided
    if (!empty($semester)) {
        // Replace this with your actual logic to fetch batches based on semester
        // Example: $batches = fetchBatchesBySemester($semester);

        // Simulate fetching batches based on the selected semester from the sregister table
        $conn = new mysqli("localhost", "root", "", "pragati");
        if ($conn->connect_errno != 0) {
            die("Connection failed");
        }

        $sql = "SELECT DISTINCT batch FROM sregister WHERE sem = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $semester);
        $stmt->execute();
        $result = $stmt->get_result();

        $batches = array();
        while ($row = $result->fetch_assoc()) {
            $batches[] = $row['batch'];
        }

        // Close the database connection
        $conn->close();

        // Return the batches as JSON response
        echo json_encode($batches);
    } else {
        // Handle the case where the semester parameter is not provided
        $response = array("error" => "Semester parameter is required");
        echo json_encode($response);
    }
} else {
    // Handle the case where the semester parameter is missing
    $response = array("error" => "Semester parameter missing");
    echo json_encode($response);
}
?>
