<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

$row = $_SESSION['user'];
$studentId = $row['id'];
$name = $row['name'];

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Create a connection
$conn = new mysqli("localhost", "root", "", "pragati");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch subject-wise marks for the logged-in student
function getStudentMarks($conn, $studentId)
{
    $sql = "SELECT subject, exam, marks FROM marks WHERE student_id = $studentId";
    $result = $conn->query($sql);

    $studentMarks = [];

    while ($row = $result->fetch_assoc()) {
        $subject = $row['subject'];
        $exam = $row['exam'];
        $marks = $row['marks'];

        $studentMarks[$subject][$exam] = $marks;
    }

    return $studentMarks;
}

// Get subject-wise marks for the logged-in student
$studentMarks = getStudentMarks($conn, $studentId);

// Close the connection
$conn->close();
?>

<?php include('studentdashboard.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>

.content-container {
    display: flex;
    align-items: center;
}

#container {
    text-align: center;
    padding: 20px;
    background-color: #f0f0f0;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
}

#greeting {
    font-size: 24px;
    font-weight: bold;
    text-align: left;
}

#lastRefresh {
    margin-top: 10px;
    text-align: right;
}

.exam-dropdown {
    margin-top: 10px;
}

</style>


</head>

<body>
<div class="main">
        <div id="container">
            <div id="greeting"></div>
            <div id="lastRefresh">Last Refreshed: <span id="refreshTime"></span></div>
            <div class="exam-dropdown">
                <label for="examType">Select Exam Type:</label>
                <select id="examType" name="examType">
                    <?php
                    // Extract unique exam types from the student marks
                    $allExams = [];
                    foreach ($studentMarks as $subjectMarks) {
                        $allExams = array_merge($allExams, array_keys($subjectMarks));
                    }
                    $uniqueExams = array_unique($allExams);

                    // Generate options for the dropdown
                    foreach ($uniqueExams as $exam) {
                        echo "<option value='$exam'>$exam</option>";
                    }
                    ?>
                </select>
            </div>
        </div><br><br>
        <canvas id="batchChart" width="400" height="200"></canvas>
    </div>
    <script>
        // Pass the user's name from PHP to JavaScript
        var username = '<?php echo $name; ?>';

        function getGreeting() {
            const currentTime = new Date();
            const currentHour = currentTime.getHours();

            let greeting = '';

            if (currentHour >= 5 && currentHour < 12) {
                greeting = 'Good morning';
            } else if (currentHour >= 12 && currentHour < 18) {
                greeting = 'Good afternoon';
            } else {
                greeting = 'Good evening';
            }

            return greeting;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const greetingElement = document.getElementById('greeting');
            const greeting = getGreeting();

            // Replace with actual username
            const message = `${greeting}, ${username}!`;

            greetingElement.textContent = message;
            const refreshTimeElement = document.getElementById('refreshTime');
            const currentTime = new Date();
            const formattedTime = currentTime.toLocaleString();
            refreshTimeElement.textContent = formattedTime;
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Get the subject-wise marks from PHP
            const studentMarks = <?php echo json_encode($studentMarks); ?>;

            // Extract labels (subjects) and data (marks) from studentMarks
            const subjects = Object.keys(studentMarks);
            const examTypes = Object.keys(studentMarks[subjects[0]]);
            const marksData = subjects.map(subject => examTypes.map(examType => studentMarks[subject][examType]));

            // Create a bar chart
            var ctx = document.getElementById('batchChart').getContext('2d');
            var batchChart;

            function updateChart(selectedExam) {
                // Filter marks based on the selected exam
                const filteredMarks = subjects.map(subject => studentMarks[subject][selectedExam]);

                // Update the chart data
                batchChart.data.labels = subjects;
                batchChart.data.datasets[0].data = filteredMarks;
                batchChart.update();
            }

            // Initial chart creation
            batchChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: subjects,
                    datasets: [{
                        label: 'Marks',
                        data: marksData[0],
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Marks'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Subjects'
                            }
                        }
                    }
                }
            });

            // Event listener for exam type dropdown change
            document.getElementById('examType').addEventListener('change', function () {
                const selectedExam = this.value;
                updateChart(selectedExam);
            });
        });
    </script>
</body>

</html>