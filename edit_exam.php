<?php
if(isset($_POST['submit']))
{
    $conn= new mysqli("localhost","root","","pragati");
    if($conn->connect_errno!=0)
    {
        die("connection failed");
    }
    $batch = $_POST['batch'];
    $sem = $_POST['sem'];
    $exam = $_POST['exam'];
    $id=$_POST['id']; 
   
    $sql="UPDATE exam_register SET batch='$batch',sem='$sem',exam='$exam'WHERE id='$id'";
    if($result = $conn->query($sql))
    { 
       echo ("<script> alert('Information updated Successfully');</script>");
        header("location:exam.php");
    }
    else{
        echo "Error";
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

        input[type="text"],input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

     
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
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

           /* Style for the container */
.input-boxa {
    margin-bottom: 20px;
}

/* Style for the label */
.input-boxa label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #333;
}

/* Style for the select element */
.input-boxa select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #fff;
    transition: border-color 0.3s ease-in-out;
}

/* Style for the select element when focused */
.input-boxa select:focus {
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
}

 

    
</style>

</head>
<body>
    <?php
    if(isset($_POST['edit']))
    {
        ?>
    
    <form action="edit_exam.php" method="POST"  style="border:1px solid #ccc" autocomplete="off">
    <?php
     $conn= new mysqli("localhost","root","","pragati");
    if($conn->connect_errno!=0)
    {
        die("connection failed");
    }
    $id=$_POST['exam_edit'];
    $sql="SELECT * FROM exam_register where id='$id'";
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
            <legend>Update Details</legend>

          
            <div class="input-box">
                        <label>Batch</label>
                        <input type="number" name="batch"value="<?php echo $row['batch']?>"  required/>
                    </div>
        
                    <div class="input-box">
                        <label>Semester</label>
                        <input type="number"name="sem"min="1" max="8"value="<?php echo $row['sem']?>" required />
                    </div>
                    
                    <div class="input-boxa">
    <label for="exam">Select an Exam:</label>
    <select id="exam" name="exam">
        <option value="Mid term" <?php if ($row['exam'] == "Mid term") echo "selected"; ?>>Mid term</option>
        <option value="Preboard" <?php if ($row['exam'] == "Preboard") echo "selected"; ?>>Preboard</option>
        <option value="Board" <?php if ($row['exam'] == "Board") echo "selected"; ?>>Board</option>
    </select>
</div>

                  
                         
  
                      <div class="clearfix">
                <input type="submit"value="Update" name="submit">
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