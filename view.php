<?php

if (isset($_POST['view'])) {
    $conn = new mysqli("localhost", "root", "", "pragati");
    if ($conn->connect_errno != 0) {
        die("Connection failed");
    }

    $id = $_POST['student_view'];
    $sql = "SELECT * FROM sregister WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Error";
    }
}

if (isset($_POST['submit'])) {
    $conn = new mysqli("localhost", "root", "", "pragati");
    if ($conn->connect_errno != 0) {
        die("Connection failed");
    }

    $id = $_POST['id'];
    $status = $_POST['status'];
    $batch = $_POST['batch'];
    $sem = $_POST['sem'];

    $sql = "UPDATE sregister SET status='$status', batch='$batch', sem='$sem' WHERE id='$id'";
    
    if ($conn->query($sql)) {
        echo ("<script> alert('Student Information Updated Successfully');</script>");
        header("location: student.php");
    } else {
        echo "Error updating student information: " . $conn->error;
    }

    $conn->close();
}
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
/* Styles for the update form */
form {
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    form h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
    }

    input[type="text"],
    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: url('arrow-down.png') no-repeat right;
        background-size: 20px;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>
<?php
include('dashboard.php');?>
<div class="profile-box">
    <!-- Your existing student profile code here -->
    <div class="card-container">
        <div class="card">
      <!-- Your student profile card #1 -->
            <div class="profile-image">
                <img src="img/60111.jpg" alt="Admin Image">
             </div>
             <!-- Fetch data from the database and display it -->
             <h2>Name: <?php echo ucwords($row['name']); ?></h2>
        <p>Student ID: <?php echo $row['id']; ?></p>
        <p>Email: <?php echo $row['email']; ?></p>
           
      
    </div>
    <div class="card">
      <!-- Your student profile card #2 -->
      <h2>General Information</h2>
      <div class="table-container">
      <table class="student-table">
                <tr>
                    <td>ID</td>
                    <td>:</td>
                    <td><?= $row['id']; ?></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>:</td>
                    <td><?= $row['address']; ?></td>
                 </tr>
                 <tr>
                    <td>Date of Birth</td>
                    <td>:</td>
                    <td><?= $row['dob']; ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td><?= $row['email']; ?></td>
                </tr>
                <tr>
                    <td>Contact No</td>
                    <td>:</td>
                    <td><?= $row['contact']; ?></td>
                </tr>
                <tr>
                    <td>Batch</td>
                    <td>:</td>
                    <td><?= $row['batch']; ?></td>
                </tr>
                <tr>
                    <td>Current Semester</td>
                    <td>:</td>
                    <td><?= $row['sem']; ?></td>
                </tr>
              
                </table>
              
      </div>
    </div>
    
    <div class="card">
    <!-- New card to be inserted below the General Information card -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"autocomplete="off">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <h2>Update Information</h2>
                    <label for="batch"><b>Batch:</b></label>
<input type="text" name="batch" id="batchCell"min="2075"max="2080" value="<?php echo $row['batch']; ?>" <?php echo ($row['status'] == "Enrolled") ? "readonly" : ""; ?> required/><br>

<label for="sem"><b>Semester:</b></label>
<input type="text" name="sem" id="sem" min="1" max="8" value="<?php echo $row['sem']; ?>" <?php echo ($row['status'] == "Enrolled") ? "readonly" : ""; ?> required/><br>


                    
        <label><b>Status</b></label>
<div class="column">
    <div class="input-box">
        <?php
        if ($row['status'] == "Enrolled") {
            // If the status is "Enrolled," display it as plain text
            echo '<input type="text" name="status" id="status" value="Enrolled" readonly>';
        } else {
            // If the status is "Pending," show it as a dropdown for selection
            echo '<div class="select-box">
                    <select name="status" id="status">
                        <option value="Pending" ' . ($row['status'] == "Pending" ? 'selected' : '') . '>Pending</option>
                        <option value="Enrolled">Enrolled</option>
                    </select>
                  </div>';
        }
        ?>
                  
    </div>
    </div>

                    <input type="submit" name="submit" value="Update">
                </form>
   

</div>
   
  </div>
  </div> 
  
</body>
</html>



