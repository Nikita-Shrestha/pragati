
<?php
if(isset($_POST['submit']))
{
    $conn= new mysqli("localhost","root","","pragati");
    if($conn->connect_errno!=0)
    {
        die("connection failed");
    }
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $id=$_POST['id']; 
   
    $sql="UPDATE sregister SET name='$name',email='$email',contact='$contact',dob='$dob',gender='$gender',address='$address'WHERE id='$id'";
    if($result = $conn->query($sql))
    { 
       echo ("<script> alert('Information updated Successfully');</script>");
        header("location:student.php");
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
    if(isset($_POST['update']))
    {
        ?>
    
    <form action="studentupdate.php" method="POST"  style="border:1px solid #ccc" autocomplete="off">
    <?php
     $conn= new mysqli("localhost","root","","pragati");
    if($conn->connect_errno!=0)
    {
        die("connection failed");
    }
    $id=$_POST['student_update'];
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
            <legend>Update Details</legend>

          
            <label><b>Full Name</b></label>
            <input type="text" name="name" pattern="^[A-Za-z\s]+$" value="<?php echo $row['name']?>"required />
        
            <label><b>Email Address</b></label>
            <input type="email" pattern=".+@email\.com" title="Enter valid email" name="email" value="<?php echo $row['email']?>" required />
                      
            <label><b>Address</b></label>
            <input type="text"  name="address" pattern="^[a-zA-Z0-9\s]*$" value="<?php echo $row['address']?>" required />  
        

            <label><b>Contact Number</b></label>
            <input type="text" placeholder="Enter phone number" name="contact" pattern="[1-9]{1}[0-9]{9}" value="<?php echo $row['contact']?>" required />
            <label><b>Birth Date</b></label>

          <div class="input-box">
            
            <input type="date" name="dob" min="1993-1-1" max="2005-12-31" id="dob" value="<?php echo $row['dob']?>"required />
          </div>

        <div class="gender-box">
          <label><b>Gender</b></label>
          <div class="gender-option" >
                <div class="gender" >
                <input type="radio" name="gender" <?php if ($row['gender'] == "female") echo "checked"; ?> value="Female">Female
                <input type="radio" name="gender" <?php if ($row['gender'] == "male") echo "checked"; ?> value="Male">Male

                </div>
           
            </div>
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