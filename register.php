<?php
if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pw = $_POST['pw']; // Assuming the plain password
    $cpw = $_POST['cpw'];
    $contact = $_POST['contact'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    
    // Hash the password before storing it in the database
    $hashedPassword = password_hash($pw, PASSWORD_DEFAULT);
    $hashedcpw = password_hash($cpw, PASSWORD_DEFAULT);
    
    $conn = new mysqli("localhost", "root", "", "pragati");
    
    if($conn->connect_error) {
        die("Connection error");
    }
    
    $check = "SELECT * FROM sregister WHERE email='".$email."' OR contact='".$contact."'";
    $result = $conn->query($check);
    
    if($result->num_rows > 0) {
        echo '<script>alert("Username already exists")</script>';
    } else {
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO sregister(name, email, pw, cpw, contact, dob, gender, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        
        // Bind parameters
        $stmt->bind_param("ssssssss", $name, $email, $hashedPassword, $hashedcpw, $contact, $dob, $gender, $address);
        
        if($stmt->execute()) {
            echo '<script>alert("Student registered successfully!")</script>';
        } else {
            echo('<script> alert("Something went wrong. Please try again!");</script>');
        }
        
        // Close the statement
        $stmt->close();
    }
    
    // Close the database connection
    $conn->close();
}
?>



<!DOCTYPE html>
<!---Coding By CodingLab | www.codinglabweb.com--->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <!--<title>Registration Form in HTML CSS</title>-->
    <!---Custom CSS File--->
    <link rel="stylesheet" href="register.css" />
  </head>
  <body>
    
    <section class="container">
      <header>Registration Form</header>
      <form action="register.php" class="form"method="post" autocomplete="off">
      <div class="input-box">
    <label>Full Name</label>
    <input type="text" name="name" id="fullname" placeholder="Enter full name" required />
</div>

        <div class="input-box">
          <label>Email Address</label>
          <input type="email" pattern=".+@email\.com" title="Enter valid email" placeholder="Enter email address" name="email" required />
        </div>
        <div class="input-box address" >
          <label>Address</label>
          <input type="text" placeholder="Enter your address" name="address" pattern="^[a-zA-Z0-9\s]*$" required />   
        </div>
        <div class="column">
        <div class="input-box">
          <label>Password</label>
          <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="Enter password" name="pw" required />
              
        </div>
        <div class="input-box">
            <label>Confirm Password</label>
            <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="Confirm your password" name="cpw" required />
          </div>
          </div>
        

        <div class="column">
          <div class="input-box">
            <label>Contact Number</label>
            <input type="text" placeholder="Enter phone number" name="contact" pattern="[1-9]{1}[0-9]{9}" required />
          </div>
          <div class="input-box">
            <label>Birth Date</label>
    
            <input type="date" name="dob" min="1993-1-1" max="2005-12-31" id="dob" required />
          </div>
        </div>
        <div class="gender-box">
          <h3>Gender</h3>
          <div class="gender-option" >
            <div class="gender" >
              <input type="radio" id="check-male" name="gender"value="male"checked />
              <label for="check-male">Male</label>
            </div>
            <div class="gender">
              <input type="radio"id="check-female" name="gender" value="female"/>
              <label for="check-female">Female</label>
            </div>
           
          </div>
        </div>
      
     <button type="submit" name="submit">Submit</button>
       <h5 align="center">Already registered? <a href="login.php">Please Login</a></h5>
      </form>
    </section>
    <script src="jquery-3.7.0.min.js"></script>
    <script>
      
      document.addEventListener("DOMContentLoaded", function() {
        var pw = document.querySelector('input[name="pw"]');
        var cpw = document.querySelector('input[name="cpw"]');
        var form = document.querySelector("form");
      
    
        
        form.addEventListener("submit", function(event) {
          if (pw.value !== cpw.value) {
            event.preventDefault(); 
            alert("Password and confirm password do not match");
          }
        });

        
     });
     document.getElementById("fullname").onblur = function() {
    const fullNameInput = this.value;
    
    // Check if the input contains only one word (first name)
    if (!/^\S+\s+\S+$/.test(fullNameInput)) {
        alert("Full name is required. Please enter both the first name and last name.");
      
    }
};
</script>
  </body>
</html>