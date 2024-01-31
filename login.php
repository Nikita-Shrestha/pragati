<?php
session_start();
if(isset($_POST['login']))
{
    $email = $_POST['email'];
    $pw = $_POST['pw'];
    $utf8Password = mb_convert_encoding($pw, 'UTF-8');
    $conn = new mysqli("localhost", "root", "", "pragati");
    if($conn->connect_error)
    {
        die("Connection error");
    }

    $sql = "SELECT * FROM sregister WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedPassword = trim($row['pw']); // Trim any whitespace from the stored hash
    
        // Check if the user's status is "Enrolled" to allow login
        if ($row['status'] == "Enrolled") {
            if (password_verify($utf8Password, $storedPassword)) {
                $_SESSION['user'] = $row;
    
                header("Location: studenthome.php");
            } else {
                echo "<script>alert('Invalid password');</script>";
            }
        } else {
            echo "<script>alert('Student not enrolled');</script>";
        }
    }
    else {
        echo "<script>alert('User not found');</script>";
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page </title>

    <link rel="stylesheet" href="login.css">
    <style>
       select {
        margin-bottom: 10px;
        margin-top: 10px;
        outline: 0;
        font-size: 1rem;
        background: transparent;
        color:  #0a0a0a;
        border: 1px solid crimson;
        padding: 4px;
        border-radius: 9px;
      }
      </style>
</head>

<body>
     <section>
        <div class="login-box">
          <form id="login" method="post" action="login.php" autocomplete="off">
            <h2>Login</h2>
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="mail"></ion-icon>
                </span>
                <input type="email" name="email" id="email" required>
                <label>Email</label>
            </div>

            <div class="input-box">
                <span class="icon">
                    <ion-icon name="lock-closed"></ion-icon>
                </span>
                <input type="password" name="pw" id="pw" required>
                <label>Password</label>
            </div>
        
            <button type="submit" name="login">Login</button>
            <div class="register-link">
                <p>New Student? <a href="register.php">Register</a></p>
            </div>
        </form>
    </div>
  </section>
    

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>
