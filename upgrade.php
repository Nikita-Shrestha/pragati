<?php
if (isset($_POST['submit'])) {
    $conn = new mysqli("localhost", "root", "", "pragati");
    if ($conn->connect_errno != 0) {
        die("Connection failed");
    }

    $id = $_POST['id'];
    $update_date = $_POST['update_date'];
    $updated_by = $_POST['updated_by'];

    // Set the current semester to "Completed"
    $status = "Completed";

    // Get the current semester value from the form
    $current_sem = $_POST['sem'];

    // Calculate the next semester value
    $next_sem = $current_sem + 1;

    // Check if there are records in the marks table for the current semester
    $marksCheckSql = "SELECT COUNT(*) as count FROM marks WHERE student_id='$id' AND sem='$current_sem'";
    $marksResult = $conn->query($marksCheckSql);
    $marksRow = $marksResult->fetch_assoc();
    $marksCount = $marksRow['count'];

    if ($marksCount > 0) {
        // Insert the data into the new table (semester_record)
        $insertSql = "INSERT INTO semester_record (std_id, sem, update_date, updated_by, status) VALUES ('$id', '$current_sem', '$update_date', '$updated_by', '$status')";

        if ($conn->query($insertSql)) {
            // Update the student's current semester in the original table
            $updateSql = "UPDATE sregister SET sem='$next_sem', status='Enrolled' WHERE id='$id'";
            if ($conn->query($updateSql)) {
                echo ("<script> alert('Information updated and inserted successfully');</script>");
                header("location: student.php");
            } else {
                echo ("<script> alert('Error updating student\'s current semester: " . $conn->error . "');</script>");
            }
        } else {
            echo ("<script> alert('Error inserting data into the new table: " . $conn->error . "');</script>");
        }
    } else {
        echo ("<script> alert('Error: No records found in the marks table for the current semester.');</script>");
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Page</title>

        <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .design{
            width: 80%;
            margin: auto;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #B9D9EB;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            height: 600px; /* Set a fixed height for the form */
            overflow-y: auto; /* Add vertical scrollbar if content overflows */
       
        }

        legend {
            font-weight: bold;
            font-size: 18px;
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

       
     
        input[type="submit"] {
            background-color: #0530ad;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .column {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .column .input-box,
.column .select-box {
    width: 50%; /* Adjust as needed */
}
    
        fieldset {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 20px;
    margin: 20px auto; 
    width:60%;
    background-color: #f9f9f9;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }
 
</style>

</head>

<body>
<?php
    if(isset($_POST['upgrade']))
    {
        ?>
            
    <form action="upgrade.php" method="POST"  style="border:1px solid #ccc" autocomplete="off">
    <?php
     $conn= new mysqli("localhost","root","","pragati");
    if($conn->connect_errno!=0)
    {
        die("connection failed");
    }
    $id=$_POST['student_upgrade'];
    $sql="SELECT * FROM sregister where id='$id'";
            if($result = $conn->query($sql))
            { $row = $result->fetch_assoc();}
           
            else{
                echo "Error";
            }
       
        ?>
            <?php
include('dashboard.php')?>
        <div class="design">
        <fieldset> 

            <br> 
            <legend>Upgrade Semester</legend>
            <label><b>Student ID:</b></label>
            <input type="text"  name="id" value="<?php echo $row['id']?>" readonly/>
        
            <label><b>Student Name:</b></label>
            <input type="text"  name="name" value="<?php echo $row['name']?>" readonly/>
            <label><b>Updated By:</b></label>
            <input type="text"  name="updated_by" value="Admin" readonly/>
                             
            <label><b>Updated Date:</b></label>
            <input type="text"  name="update_date" value="<?php echo date('Y-m-d'); ?>" readonly />  
        
            <!-- ... (your existing HTML form) ... -->
    <label><b>Current Semester:</b></label>
    <input type="text" name="sem" value="<?php echo $row['sem']; ?>" readonly />

    <!-- Add a hidden input to store the current semester as "Completed" -->
    <input type="hidden" name="status" value="Completed" readonly />
    
    <!-- ... (rest of your HTML form) ... -->

            <label><b>Status</b></label>
<div class="column">
    <div class="input-box">
        <?php
        if ($row['status'] == "Completed") {
            // If the status is "Completed," display it as plain text
            echo '<input type="text" name="status" id="status" value="Completed" readonly>';
        } else {
            // If the status is "Pending," set it to "Running" by default
            $defaultStatus = "Running";
            echo '<div class="select-box">
                    <select name="status" id="status">
                        <option value="Running" ' . ($row['status'] == "Running" ? 'selected' : '') . '>Running</option>
                        <option value="Completed">Completed</option>
                    </select>
                  </div>';
        }
        ?>
    </div>
</div>

  
                      <div class="clearfix">
                <input type="submit"value="Upgrade Semester" name="submit">
                <input type="hidden" name="id" value="<?php echo $id?>">
               
                       
                      </div>
                    </div>
           
        </fieldset>
        </div>
    </form>
    <?php
    }
    ?>
    
</body>
</html>
