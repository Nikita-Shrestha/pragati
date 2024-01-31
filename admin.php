<?php
if(isset($_POST['login']))
{
  $Uname=$_POST['Uname'];
  $Pass=$_POST['Pass'];
  $conn= new mysqli("localhost","root","","pragati");
  if($conn->connect_error)
  {
    die("Connection error");
  }
  $sql="select * from admin where username='$Uname' and password='$Pass'";
  $result=$conn->query($sql);
  if($result->num_rows> 0)
  {
    session_start();
    $row=$result->fetch_assoc();
    $_SESSION['admin']=$row;
    header("Location:home.php");
  }
  else
  {
    echo "<script language='javascript'>";
    echo "alert('Login error')";
    echo "</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animated Login Page </title>
    <link rel="stylesheet" href="login.css">
</head>

<body>
     <section>
    <div class="login-box">
    <form id="login" method="post" action="admin.php" autocomplete="off">
            <h2>Login</h2>
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="mail"></ion-icon>
                </span>
                <input type="text" name="Uname" id="Uname"required>
                <label>Username</label>
            </div>

            <div class="input-box">
                <span class="icon">
                    <ion-icon name="lock-closed"></ion-icon>
                </span>
                <input type="Password" name="Pass" id="Pass"required>
                <label>Password</label>
            </div>

           

            <button type="submit" name="login" id="log"class="btn btn-info btn-lg gradient-custom-4 text-body">Login</button>
          



        </form>
    </div>
    </section>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>