<?php
if(isset($_POST['submit']))
{
    $conn= new mysqli("localhost","root","","pragati");
    if($conn->connect_errno!=0)
    {
        die("connection failed");
    }
    $sem_id = $_POST['sem_id'];
    $sname = $_POST['sname'];
    $code = $_POST['code'];
    $cr_hrs = $_POST['cr_hrs'];
    $id=$_POST['id']; 
   
    $sql="UPDATE subject SET sem_id='$sem_id',sname='$sname',code='$code',cr_hrs='$cr_hrs'WHERE id='$id'";
    if($result = $conn->query($sql))
    { 
       echo ("<script> alert('Information updated Successfully');</script>");
        header("location:subject.php");
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

        input[type="text"],
         input[type="email"],
        input[type="password"],
     input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="radio"] {
            margin-right: 5px;
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

    

    
</style>

</head>
<body>
    <?php
    if(isset($_POST['edit']))
    {
        ?>
    
    <form action="edit.php" method="POST"  style="border:1px solid #ccc" autocomplete="off">
    <?php
     $conn= new mysqli("localhost","root","","pragati");
    if($conn->connect_errno!=0)
    {
        die("connection failed");
    }
    $id=$_POST['subject_edit'];
    $sql="SELECT * FROM subject where id='$id'";
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
                        <label>Sem_ID</label>
                        <input type="number" name="sem_id" value="<?php echo $row['sem_id']?>"required  />
                    </div>
        
                    <div class="input-box">
                        <label>Subject Name</label>
                        <input type="text"name="sname"value="<?php echo $row['sname']?>" required />
                    </div>
                    <div class="input-box address" >
                        <label>Subject Code</label>
                        <input type="text"  name="code" pattern="^[a-zA-Z0-9\s]*$" value="<?php echo $row['code']?>"required />   
                    </div>
                     <div class="input-box">
                        <label>Credit Hours</label>
                        <input type="number"name="cr_hrs" value="<?php echo $row['cr_hrs']?>" required />
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