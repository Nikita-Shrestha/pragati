<?php
session_start();
// Fetch student information from the sregister table
$row = $_SESSION['user'];
$id = $row['id'];
$email=$row['email'];
$address=$row['address'];
$dob=$row['dob'];
$contact=$row['contact'];
$batch=$row['batch'];
$sem=$row['sem'];


$connection = new mysqli("localhost", "root", "", "pragati");
if ($connection->connect_error != 0) {
    die("Database Connectivity Error");
}

$studentQuery = "SELECT * FROM sregister WHERE id = ?";
$studentStmt = $connection->prepare($studentQuery);
$studentStmt->bind_param("s", $id);
$studentStmt->execute();
$studentResult = $studentStmt->get_result();

if ($studentResult->num_rows > 0) {
    $studentRow = $studentResult->fetch_assoc();
    // Student data fetched successfully
    
} else {
    // Handle case where student data is not found
}

$studentStmt->close();
$connection->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Styles for the wrapping box */
.profile-box {
  background-color: #ffffff;
  border: 1px solid #dee2e6;
  padding: 50px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  width: 80%;
  margin: 10px 20px 15px 50px;;
  height: 600px; /* Set a fixed height for the form */
 overflow-y: auto; /* Add vertical scrollbar if content overflows */
}
/* Styles for the card container */
.card-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  grid-gap: 20px;
  padding: 20px;
}

/* Styles for each card */
.card {

  background-color: #ffffff;
  border: 1px solid #dee2e6;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
/* Styles for the second row */
.card:nth-child(2)
 {
  grid-column: span 2;
}
.card:nth-child(3)
 {
  grid-column: span 3;
}
.profile-image img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 5px solid #333;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}
/* Styles for the table container */
.table-container {
  overflow-x: auto;
}

/* Styles for the student table */
.student-table {
  width: 100%;
  border-collapse: collapse;
  border: 1px solid #dee2e6;
  margin-top: 10px;
  
}

.student-table td {
  border: 1px solid #dee2e6;
  padding: 8px;
  text-align: left;
}

</style>
</head>
<body>
<?php
include('studentdashboard.php')?>
<div class="profile-box">
    <!-- Your existing student profile code here -->
    <div class="card-container">
        <div class="card">
      <!-- Your student profile card #1 -->
            <div class="profile-image">
                <img src="img/60111.jpg" alt="Admin Image">
             </div>
             <!-- Fetch data from the database and display it -->
   <p class="mb-0"><h3><strong class="pr-1"> <b><?php echo ucwords($name); ?></b>  </strong></h3></p>
   <p class="mb-0"><strong class="pr-1">Student ID:</strong><?php echo $id; ?></p>
   <p class="mb-0"><strong class="pr-1">Email:</strong><?php echo $email; ?></p>
            
           
      
    </div>
    <div class="card">
      <!-- Your student profile card #2 -->
      <h2>General Information</h2>
      <div class="table-container">
        <table class="student-table">
          
          <tr>
            <td>ID</td>
            <td>:</td>
            <td><?php echo $id; ?></td>
          </tr>
          <tr>
            <td>Address</td> 
            <td>:</td>
            <td><?php echo $address; ?></td>
          </tr>
          <tr>
            <td>Date of Birth</td> 
            <td>:</td>
            <td><?php echo $dob; ?></td>
          </tr>
          <tr>
            <td>Email</td> 
            <td>:</td>
            <td><?php echo $email; ?></td>
          </tr>
          <tr>
            <td>Contact No</td> 
            <td>:</td>
            <td><?php echo $contact; ?></td>
          </tr>
          <tr>
            <td>Batch</td> 
            <td>:</td>
            <td><?php echo $batch; ?></td>
          </tr>
          <tr>
            <td>Semester</td> 
            <td>:</td>
            <td><?php echo $sem; ?></td>
          </tr>
        </table>
      </div>
    </div>
    
 
   
  </div>
  </div> 
</body>
</html>

