<?php
include('dashboard.php');
function getStatusClass($status) {
    if ($status === 'Enrolled') {
        return 'Enrolled';
    } elseif ($status === 'Pending') {
        return 'Pending';
    } else {
        return ''; // Handle other statuses or errors
    }
  }?>

<?php


// Create a connection
$conn = new mysqli("localhost","root","","pragati");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to count the number of students with a specific status
function countStudentsByStatus($conn, $status)
{
    $sql = "SELECT COUNT(*) AS count FROM sregister WHERE status = '$status'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Get the count of enrolled and pending students
$enrolled_students_count = countStudentsByStatus($conn, 'Enrolled');
$pending_students_count = countStudentsByStatus($conn, 'Pending');

// Function to count the number of records in a table
function countRecords($conn, $table)
{
    $sql = "SELECT COUNT(*) AS count FROM $table";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Get the count of students and teachers
$students_count = countRecords($conn, 'sregister');


// Function to count the number of students in each batch
function countStudentsByBatch($conn)
{
    $sql = "SELECT batch, COUNT(*) AS count FROM sregister GROUP BY batch";
    $result = $conn->query($sql);
    $batch_counts = array();

    while ($row = $result->fetch_assoc()) {
        $batch_counts[$row['batch']] = $row['count'];
    }

    return $batch_counts;
}

// Get the count of students in each batch
$batch_counts = countStudentsByBatch($conn);

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
         table{
      width:100%;
      margin-left: 30px;
      border-collapse: collapse;
      border: 3px solid	#808080;
    }

    th, td{
      border: 3px solid #ddd;
      text-align: left;
      padding: 10px;
    }
    tr:hover {
      background-color:#e7e7e7;
    }
    .view{
cursor:pointer;
  background-color: #0530ad;
  border:none;
  border-radius: 4px;
  color: white;
  padding: 8px 15px; 
  margin-right: 0px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 15px; 
   box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24),0 17px 50px 0 rgba(0,0,0,0.19);
}
.verify{
  cursor:pointer;
  background-color: #4CAF50;
  border:none;
  border-radius: 5px;
  color: white;
padding: 10px 15px; 
margin-right: 0px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px; 
   box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24),0 17px 50px 0 rgba(0,0,0,0.19);
}
.Pending {
    color: red;
    /* Additional styling for pending status here */
}
.table-container {
        max-height: 500px; /* Set the maximum height for the container */
        overflow-y: auto; /* Enable vertical scrollbar when content exceeds the height */
       
        margin-top: 20px; /* Optional: Add margin for spacing */
    }  
  
    .verify:hover{
            background-color: #45a049;
        }
        .view:hover {
    background-color: #002080;
}

    .box:nth-child(1) {
background-color: #1a1f71;
}
.box:nth-child(2) {
background-color:  #1a1f71;
}
.box:nth-child(3) {
background-color:  #1a1f71;
}
.box:nth-child(4) {
background-color:  #1a1f71;
}    
        .recent-Articles {
font-size: 30px;
font-weight: 600;
color:#1a1f71;
}


    /* Adjust the size of box1 */
    .box1 ,.box2,.box3{
        width: 200px;
        height: 200px;
    }

    .box .text {
color: white;
margin-right: 60px;
} 
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

        </style>
</head>
<body>
   
<div class="main">
<div id="container">
<div id="greeting"></div>
<div id="lastRefresh">Last Refreshed: <span id="refreshTime"></span></div>
    </div><br><br>

 <div class="box-container">
 
     <div class="box box1">
     <canvas id="myPieChart"></canvas>
     </div>
     <div class="box box3">
        <div class="content-container">
         <div class="text">
             <h1 class="topic-heading"><?php echo $students_count; ?></h1>
             <h2 class="topic">Students</h2>
         </div>

         <img src="img/students.png">
     </div>
     </div>
     <div class="box box2">
    <canvas id="myBarChart"></canvas>
</div>

    
 </div>
<br><br>
<h4>Recently Registered Students</h4>
 <!-- Add a table below the boxes -->
<div class="table-container">
    <table>
    <thead>
                    <tr>
                      <th scope="col">SN</th>
                      <th scope="col">Name</th>
                      <th scope="col">Email</th>
                      <th scope="col">Contact</th>
                      <th scope="col">Batch</th>
                      <th scope="col">Semester</th>
                      <th scope="col">Status</th>
                      <th scope="col">Action</th>

               
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $conn= new mysqli("localhost","root","","pragati");
                      if($conn->connect_error)
                      {
                      die("Connection error");
                      }
                      $sql="SELECT * from sregister where status='pending'";
                      $result=$conn->query($sql);
                      $serialNumber=1;// Initialize the serial number
                      while($row=$result->fetch_assoc())
                      {
                      echo "
                      <tr>
                      <td>".$serialNumber."</td>
                      <td>".$row['name']."</td>
                      <td>".$row['email']."</td>
                      <td>".$row['contact']."</td>
                      <td data-batch='".$row['batch']."'>".$row['batch']."</td> 
                      <td data-semester='".$row['sem']."'>".$row['sem']."</td>
                      <td class='status-cell " . getStatusClass($row['status']) . "'>" . $row['status'] . "</td>

                
                 <td>
                 <form action='view.php' method='post'>
                 
                 <input type='hidden' value='".$row['id']."'name='student_view'>
 
                 <input type='submit' class='view btn' value = 'View' name='view'>
                 </form>
                 </td>   
                     
                </tr>
                        ";
                        $serialNumber++;// Increment the serial number for the next row
                      }
                   ?>
    
                  </tbody>
                
    </table>
</div>


     </div>
 </div>

</div>
</div>
</body>
<script>
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

        document.addEventListener('DOMContentLoaded', function() {
            const greetingElement = document.getElementById('greeting');
            const greeting = getGreeting();
            
            const username = 'Admin'; // Replace with actual username
            const message = `${greeting}, ${username}!`;
            
            greetingElement.textContent = message;
            const refreshTimeElement = document.getElementById('refreshTime');
            const currentTime = new Date();
            const formattedTime = currentTime.toLocaleString();
            refreshTimeElement.textContent = formattedTime;
        });
    </script>
<script>
  
   // Get the data for the pie chart
var pieChartData = {
    labels: ['Enrolled Students', 'Pending Students'],
    datasets: [{
        data: [<?php echo $enrolled_students_count; ?>, <?php echo $pending_students_count; ?>],
        backgroundColor: ['#1a1f71', '#ff0000'], // Colors for the pie chart segments
    }]
};

// Get the data for the bar chart
var barChartData = {
    labels: <?php echo json_encode(array_keys($batch_counts)); ?>,
    datasets: [{
        label: 'Number of Students',
        data: <?php echo json_encode(array_values($batch_counts)); ?>,
        backgroundColor: '#a2c11c', // Color for the bars
    }]
};

    // Get the canvas element where the pie chart will be rendered
    var ctx = document.getElementById('myPieChart').getContext('2d');

    // Create the pie chart
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: pieChartData,
    });


   
    // Get the canvas element where the bar chart will be rendered
    var ctxBar = document.getElementById('myBarChart').getContext('2d');

    // Create the bar chart
    var myBarChart = new Chart(ctxBar, {
        type: 'bar',
        data: barChartData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Students'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Batch'
                    }
                }
            }
        }
    });


</script>

<script src="dashboard.js"></script>
</html>