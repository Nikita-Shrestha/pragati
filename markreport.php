<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sidebar Menu for Admin Dashboard | CodingNepal</title>
  <link rel="stylesheet" href="dashboard.css">
  <link rel="stylesheet" href="../fontawesome/css/all.min.css">
</head>
<body>
  <?php
  session_start(); 
  if(!isset($_SESSION['user'])) {
    header("Location:admin.php"); 
  }
  $row=$_SESSION['user']; 
  $id=$row['id']; 
  if(isset($_POST['logout'])) {
    session_destroy(); 
    header("Location:admin.php");  
  }

$connection = new mysqli("localhost", "root", "", "pragati");
if ($connection->connect_errno != 0) {
    die("Connection failed");
}
?>

<main class="main">
    <br><br><br>
    <div>
        <h1>Birth Record Report</h1>
        <form method="post" action="markreport.php">
    <div class="input-boxa">
    <label for="sem">Select Semester</label>
    <select id="sem" name="sem" onchange="fetchBatch();fetchStudents();fetchSubjects();">
        <option value="">Select Semester</option> <!-- Placeholder option -->
        <?php
        $conn = new mysqli("localhost", "root", "", "pragati");
        if ($conn->connect_errno != 0) {
            die("Connection failed");
        }

        // Query to fetch running semesters
        $sql = "SELECT DISTINCT sem FROM sregister WHERE status='Enrolled'";
        $result = $conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $semester = $row['sem'];
                echo "<option value='$semester'>$semester</option>";
            }
        } else {
            echo "Error fetching semesters";
        }

        // Close the database connection
        $conn->close();
        ?>
    </select>
</div>


<div class="input-boxa">
    <label for="batch">Select Batch</label>
    <select id="batch" name="batch">
        <option value="">Select Batch</option>
    </select>
</div>

<div class="input-boxa">
    <label for="student_name">Select Student</label>
    <select id="student_name" name="student_name">
        <option value="">Select Student</option>
        <!-- Populate this dropdown with student names -->
    </select>
</div>
    

        <input type="submit" name="submit" value="Generate Report">
    </form>
    </div>

    <div>
        <?php
    if (isset($_POST['submit'])) {
    $selectedSubjects = $_POST['subjects']; // Array of selected subject IDs
    $marksData = []; // Initialize an array to store marks data

    foreach ($selectedSubjects as $subjectId) {
        // Query to fetch marks for the selected subject
        $sql = "SELECT marks_o FROM marks WHERE student_name = ? AND subject = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $id, $subjectId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $marks = []; // Initialize an array to store marks for the subject
            while ($row = $result->fetch_assoc()) {
                $marks[] = $row['marks_o'];
            }
            $marksData[] = [
                'subjectId' => $subjectId,
                'marks' => $marks,
            ];
        }
    }
}
?>
        <canvas id="chartCanvas" width="400" height="150"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const birthRecords = <?php echo json_encode($birthRecords); ?>;
            const canvas = document.getElementById('chartCanvas');
            const ctx = canvas.getContext('2d');

            const start_date = "<?php echo $start_date; ?>";
            const end_date = "<?php echo $end_date; ?>";
            const labels = [start_date, ...birthRecords.map(record => record.birthdate), end_date];

            const weights = [null, ...birthRecords.map(record => record.weight), null]; // Using null to keep the endpoints

            const genders = birthRecords.map(record => record.gender);
            const colors = genders.map(gender => gender === 'Boy' ? 'rgba(255, 0, 255, 0.8)' : 'rgba(173, 216, 230, 255)');


            const chartData = {
                labels: labels,
                datasets: [{
                  
                    label: 'Weight',
                    data: weights,
                    backgroundColor: colors,
                    borderColor: colors,
                    borderWidth: 1,
                    pointRadius: 5, // Set the point radius to make points visible
                    pointHoverRadius: 8 // Set hover radius for better visibility
                }]
            };

            new Chart(ctx, {
                type: 'scatter', // Change the type to 'scatter' for points
                data: chartData,
                options: {
                    scales: {
                        x: {
                            type: 'category',
                            labels: labels,
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Weight'
                            },
                            ticks: {
                                stepSize: 1.5, // Set the step size to 1.5
                                callback: function (value, index, values) {
                                    return value % 1.5 === 0 ? value : ''; // Display ticks at intervals of 1.5
                                }
                            }
                        }
                    }
                }
            });
        </script>
    </div>
</main>
<script>
     //batch
     function fetchBatch() {
    var semester = document.getElementById("sem").value;
    console.log("Selected Semester:", semester);
    var batchSelect = document.getElementById("batch");

    console.log("Selected Semester:", semester); // Debugging line

    // Clear existing options
    batchSelect.innerHTML = "<option value=''>Select Batch</option>";

    if (semester === "") {
        // No semester selected, so don't make the AJAX request
        return;
    }

    // Make an AJAX request to fetch batches based on the selected semester
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_batches.php?semester=" + semester, true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var batches = JSON.parse(xhr.responseText);

            console.log("Fetched Batches:", batches); // Debugging line

            // Populate the "Batch" dropdown with fetched batch options
            batches.forEach(function (batch) {
                var option = document.createElement("option");
                option.value = batch;
                option.text = batch;
                batchSelect.appendChild(option);
            });
        }
    };
    xhr.send();
}

 //students
 function fetchStudents() {
    var semester = document.getElementById("sem").value;
    var studentSelect = document.getElementById("student_name");

    // Clear existing options
    studentSelect.innerHTML = "<option value=''>Select Student</option>";

    if (semester === "") {
        // No semester selected, so don't make the AJAX request
        return;
    }

    // Make an AJAX request to fetch student names
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_students.php?semester=" + semester, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var students = JSON.parse(xhr.responseText);

            // Populate the dropdown with fetched student names
            students.forEach(function (student) {
                var option = document.createElement("option");
                option.value = student.id;
                option.text = student.name; // Set the text to the student's name
                studentSelect.appendChild(option);
            });
        }
    };
    xhr.send();
}

// JavaScript function to open the modal and display the student's report
function openModal(studentReport) {
    var modal = document.getElementById("myModal");
    var closeModal = document.getElementById("closeModal");
    var reportContainer = document.getElementById("reportContainer");

    reportContainer.innerHTML = studentReport;

    modal.style.display = "block";

    closeModal.onclick = function () {
        modal.style.display = "none";
    };

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
}

    </script>

  <script src="dashboard.js"></script>
</body>
</html>