<?php
include('dashboard.php');?>
<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar Chart Example</title>
    <link rel="stylesheet" href="table.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<form id="filterForm">
    <div class="filter-group">
        <label for="subjectFilter">Select Subject:</label>
        <select id="subjectFilter" name="subjectFilter">
            <option value="">All Subjects</option>
            <!-- Populate this dropdown with subject options from your database -->
            <?php
            // Connect to the database and fetch subject options
            $conn = new mysqli("localhost", "root", "", "pragati");
            if ($conn->connect_errno != 0) {
                die("Connection failed");
            }

            $sql = "SELECT * FROM subject";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['sname'] . "</option>";
                }
            }

            $conn->close();
            ?>
        </select>
    </div>

    <div class="filter-group">
        <label for="examFilter">Select Exam:</label>
        <select id="examFilter" name="examFilter">
            <option value="">All Exams</option>
            <!-- Populate this dropdown with exam options from your database -->
            <?php
            // Connect to the database and fetch exam options
            $conn = new mysqli("localhost", "root", "", "pragati");
            if ($conn->connect_errno != 0) {
                die("Connection failed");
            }

            $sql = "SELECT * FROM exam";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['exam'] . "</option>";
                }
            }

            $conn->close();
            ?>
        </select>
    </div>

    <div class="filter-group">
        <input type="submit" name="generate" value="Filter">
    </div>
</form>

    <!-- Add a container for the bar chart -->
    <div id="barChartContainer">
        <canvas id="barChart" width="400" height="400"></canvas>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

var chart; // Declare a global variable to hold the chart instance

// Function to generate the bar chart
function generateBarChart($data) {
    var new_data;
    if (chart) {
        chart.destroy(); // Destroy the existing chart if it exists
    }

    console.log("Data:", $data);

    $.ajax({
    type: "POST",
    url: "data_algorithm.php", // Replace with the URL to your PHP script that fetches data based on filters
    data: { data: $data }, // Pass $data as an object property
    success: function (response) {
        try {
            // Parse the JSON response (assuming it contains the data for the chart)

            var marks = JSON.parse(response);
            // Assuming new_data is an array of objects
            var labels = ["Fail", "35-50 [C]", "50-75 [B]", ">80 [A]"];
            console.log(marks);

            var ctx = document.getElementById('barChart').getContext('2d');
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Marks',
                        data: marks,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toFixed(0); // Display integers as decimals with 0 decimal places
                                }
                            }
                        }
                    }
                }
            });
        } catch (e) {
            console.error("Error parsing JSON:", e);
        }
    },
    error: function (error) {
        console.error("Error:", error);
    }
});
}

// Rest of your code...


 $(document).ready(function () {
    $("#filterForm").submit(function (e) {
        e.preventDefault(); // Prevent form submission

        var selectedSubject = $("#subjectFilter").val();
        var selectedExam = $("#examFilter").val();

        // Debugging
        console.log("Selected Subject:", selectedSubject);
        console.log("Selected Exam:", selectedExam);

        // Loop through each table row and hide/show based on selected options
        $.ajax({
                    type: "POST",
                    url: "fetch_filter_exam_data.php", // Replace with the URL to your PHP script that fetches data based on filters
                    data: { subject: selectedSubject, exam: selectedExam },
                    success: function (response) {
                        // Parse the JSON response (assuming it contains the data for the chart)
                        var data = JSON.parse(response);

                        // Call the function to generate the bar chart with the filtered data
                        generateBarChart(data);
                    },
                    error: function (error) {
                        console.error("Error:", error);
                    }
                });
            });
        });
</script>
</body>
</html>
