<?php
session_start();
// Include database connection and other necessary files
include('dashboard.php');



// Retrieve subject data from the database
$conn = new mysqli("localhost", "root", "", "pragati");
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

// Define default values for semester and batch
$semesterFilter = isset($_POST['semester']) ? $_POST['semester'] : 'All';
$batchFilter = isset($_POST['batch']) ? $_POST['batch'] : 'All';

// Use prepared statement to retrieve data
$sql = "SELECT s.id, s.name AS name, s.batch, s.sem, e.exam
        FROM sregister AS s
        LEFT JOIN exam_register AS e ON s.sem = e.sem AND s.batch = e.batch
        WHERE
            (e.exam IS NOT NULL OR e.exam = '')
            AND (s.sem = ? OR ? = 'All')
            AND (s.batch = ? OR ? = 'All')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $semesterFilter, $semesterFilter, $batchFilter, $batchFilter);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .table-container {
            max-height: 600px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding-left: 100px;
            box-sizing: content-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin:auto;
            border: 2px solid #ddd;
        }

        th, td {
            border: 2px solid #ddd;
            text-align: left;
            padding: 20px;
        }

        tr:hover {
            background-color: #e7e7e7;
        }

        .edit {
            cursor: pointer;
            background-color: #0530ad;
            border: none;
            border-radius: 4px;
            color: white;
            padding: 8px 15px;
            margin-right: 0px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 15px;
            box-shadow: 0 12px 16px 0 rgba(0, 0, 0, 0.24), 0 17px 50px 0 rgba(0, 0, 0, 0.19);
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="card">
  
        <label for="semester-filter">Filter by Semester:</label>
        <select id="data-semester">
            <option value="">All</option>
            <option value="1">Semester 1</option>
            <option value="2">Semester 2</option>
            <option value="3">Semester 3</option>
            <option value="4">Semester 4</option>
            <option value="5">Semester 5</option>
            <option value="6">Semester 6</option>
            <option value="7">Semester 7</option>
            <option value="8">Semester 8</option>
            <!-- Add more options as needed -->
        </select>
   
        <label for="batch-filter">Filter by Batch:</label>
            <select id="data-batch">
                <option value="">All</option>
                <option value="2075">Batch 2075</option>
                <option value="2076">Batch 2076</option>
                <option value="2077">Batch 2077</option>
                <option value="2078">Batch 2078</option>
                <option value="2079">Batch 2079</option>
                <option value="2080">Batch 2080</option>
                <!-- Add more options as needed -->
            </select>

   
        <label for="exam-filter">Filter by Exam:</label>
        <select id="data-exam">
            <option value="">All</option>
            <option value="Mid Term">Mid Term</option>
            <option value="Preboard">Preboard</option>
            <option value="Board">Board</option>
            <!-- Add more options as needed -->
        </select>
   
        <div class="table-container" id="table-container">
            <table>
                <thead>
                <tr>
                    <th scope="col">SN</th>
                    <th scope="col">Batch</th>
                    <th scope="col">Semester</th>
                    <th scope="col" style="width: 250px;">Exam</th>
                    <th scope="col" style="width: 250px;">Student Name</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $serialNumber = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>" . $serialNumber . "</td>
                        <td data-batch='".$row['batch']."'>".$row['batch']."</td> 
                        <td data-semester='".$row['sem']."'>".$row['sem']."</td>
                        <td data-exam='".$row['exam']."'>".$row['exam']."</td> 
                        <td>" . $row['name'] . "</td>
                        <td>
                            <form action='input_marks.php?name=" . urlencode($row['name']) . "& sem=" . urlencode($row['sem']) . "& id=" . urlencode($row['id']) . "&exam=" . urlencode($row['exam']) . "' method='post'>
                                <input type='hidden' value='" . $row['id'] . "' name='view_report'>
                                <input type='submit' class='edit btn' value='Add' name='view'>
                            </form> 
                            </td>
                        
                        </form>
                        </td>
                    </tr>";
                    $serialNumber++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterTable() {
    var semesterFilter = document.getElementById("data-semester").value;
    var batchFilter = document.getElementById("data-batch").value;
    var examFilter = document.getElementById("data-exam").value;
    var table = document.querySelector("table");
    var rows = table.querySelectorAll("tbody tr");

    rows.forEach(function(row) {
        var semesterCell = row.querySelector("td[data-semester]").getAttribute("data-semester").toLowerCase();
        var examCell = row.querySelector("td[data-exam]").getAttribute("data-exam").toLowerCase();
        var batchCell = row.querySelector("td[data-batch]").getAttribute("data-batch").toLowerCase();

        // Check if the row should be displayed based on filters
        if (
            (semesterFilter === "" || semesterCell === semesterFilter.toLowerCase()) &&
            (batchFilter === "" || batchCell === batchFilter.toLowerCase()) &&
            (examFilter === "" || examCell === examFilter.toLowerCase())
        ) {
            row.style.display = ""; // Show the row
        } else {
            row.style.display = "none"; // Hide the row
        }
    });
}

// Add event listeners to update the table when the filters change
document.getElementById("data-semester").addEventListener("change", filterTable);
document.getElementById("data-batch").addEventListener("change", filterTable);
document.getElementById("data-exam").addEventListener("change", filterTable);

// Initial table filtering when the page loads
filterTable();
</script>
</body>
</html>
