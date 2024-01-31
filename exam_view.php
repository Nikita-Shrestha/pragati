<?php
session_start();
// Include database connection and other necessary files
include('studentdashboard.php');

// Handle form submission
if (isset($_POST['submit']))  {   $batch = $_POST['batch'];
    $sem = $_POST['sem'];
    $exam = $_POST['exam'];
   

    // Create a database connection
    $conn = new mysqli("localhost", "root", "", "pragati");
    if ($conn->connect_errno != 0) {
        die("Connection Error");
    }

}

// Retrieve subject data from the database
$conn = new mysqli("localhost", "root", "", "pragati");
if ($conn->connect_error) {
    die("Connection error");
}
$sql = "SELECT * FROM exam_register";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Include an external CSS file for styling -->
    <link rel="stylesheet" href="subject.css">
    <style>
   h1 {
    font-size:25px;
    text-align: left; /* Center-align the text */
}     
        /* Style for the table */
table {
    margin-top:80px;
    margin-right:320px;
    width: 100%;
    border-collapse: collapse; /* Remove spacing between table cells */
    border: 1px solid #ddd; /* Add a border around the table */
}

/* Style for table headers (th) */
th {
    background-color: #f2f2f2; /* Background color for header cells */
    text-align: left; /* Align header text to the left */
    padding: 10px; /* Add padding to header cells */
    border: 1px solid #ddd; /* Add a border to header cells */
    width: 220px;
    text-align:center;
}

/* Style for table data cells (td) */
td {
    padding: 10px; /* Add padding to data cells */
    border: 1px solid #ddd; /* Add a border to data cells */
    text-align:center;
}

/* Style for alternate rows (zebra striping) */
tr:nth-child(even) {
    background-color: #f2f2f2; /* Background color for even rows */
}

/* Style for hover effect on table rows */
tr:hover {
    background-color: #e0e0e0; /* Background color on hover */
}

        </style>

</head>
<body>
<div class="table-container">
            <h1>Exam Details</h1>
            <?php
        if ($result->num_rows > 0) {
            // Data is available, display the table
            ?>
                <table>
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Batch</th>
                        <th scope="col">Semester</th>
                        <th scope="col">Exam Name</th>
                       
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                     $serialNumber=1;
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>" . $serialNumber .  "</td>
                            <td>" . $row['batch'] . "</td>
                            <td>" . $row['sem'] . "</td> 
                            <td>" . $row['exam'] . "</td>
                            
                        </tr>";
                        $serialNumber++;// Increment the serial number for the next row
                    }
                    ?>
                    </tbody>
                </table>
                <?php
        } else {
            // No data available, display a message
            echo "<p class='no-data'>No current exams available for this semester.</p>";
        }
        ?>
            </div>
        </div>
 

</body>
</html>
