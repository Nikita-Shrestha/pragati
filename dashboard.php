<?php
if(isset($_POST['Logout']))
{
  session_destroy();
 header("Location:admin.php") ;
}

?>

<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"content="IE=edge">
    <meta name="viewport"content="width=device-width,initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet"href="dashboard.css">
    <link rel="stylesheet"href="responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
  .logo {
font-size: 27px;
font-weight: 600;
color:#1a1f71;
}

.btn {
  border: none;
  background-color: inherit;
  padding: 5px 15px;
  font-size: 20px;
  cursor: pointer;
  display: inline-block;
}
.default:hover {
  background: #3E8EDE;
}
a{
    color:#0077c0;
    font-size:25px;
}
a:link {
  text-decoration: none;
}

a img {
  height: 30px;
  padding-right: 6px; /* Add padding around the image only */
}

img {
 
  transition: opacity 0.3s ease; /* Apply a 0.3s transition on opacity */
}

img:hover {
  opacity: 0.8; /* On hover, change the opacity to 80% */
}

</style>
</head>
 
<body>
   
    <!-- for header part -->
    <header>
 
        <div class="logosec">
            <div class="logo">PRAGATI</div>
                <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png"class="icn menuicn"id="menuicn"alt="menu-icon">
            </div>
</div>
     
     
        
        <div class="dropdown-container">
          <div class="user-info">
    <img src="img/60111.jpg" alt="Dropdown Image" id="dropdown-image" class="iconn"><h5>Admin</h5>
</div>

    <div class="dropdown-content" id="dropdown">
      <!-- Add your dropdown content here -->
      <a href="profile.php"><img src="img/profile.png" width="30px" height="30px"/> Profile</a>
      <a href="admin.php"><img src="img/logout.png"width="30px" height="30px"/>Logout</a>
    </div>
  </div>
 <style>
    /* Style for the dropdown container */
.dropdown-container {
	position: relative;
	display: inline-block;
  }
  
  /* Styles for the user info section */
.user-info {
  display: flex;
  align-items: center;
  cursor: pointer;
  padding: 8px 12px;

  border-radius: 8px;
}
  /* Style for the dropdown content */
  .dropdown-content {
	  display: none;
  position: absolute;
  top: 100%;
  right: 0;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 4px;
  padding: 8px 0;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 200px;
  }
  
  /* Style for the dropdown links */
  .dropdown-content a {
    align-items: center; /* Added align-items to vertically center the icon and text */
    display: inline-block;
  padding: 8px 16px;
  margin-right: 10px;
  text-decoration: none;
  color: #333;
  }
  
  /* Style for the dropdown links on hover */
  .dropdown-content a:hover {
	background-color: #ddd;
  }
  .iconn{
    width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
    display:block;

  
  }
 
 
ul {
  list-style-type: none;
 
}
    </style>
    </header>
    <div class="main-container">
        <div class="navcontainer">
            <nav class="nav">
          
                  <ul>
                    <li> 
                    <a href="home.php"><img src="img/dashboard.png">Dashboard</a></li><br>
                    <li>
                    <a href="student.php"><img src="img/student.png">Student</a></li><br>
                  <li>
                    <a href="subject.php"><img src="img/sub.png">Subject</a></li><br>
                    <li>
                    <li>
                    <a href="exam.php"><img src="img/exam.png">Exam</a></li><br>
                    <li>
                    <li>
                    <a href="marks.php"><img src="img/exam.png">Marks</a></li><br>
                    <li>
                    <a href="report.php"><img src="img/report.png">Reports</a></li><br>
                    <li>
                    <a href="admin.php"><img src="img/logout.png">Logout</a></li><br>
                  </ul>  
              </nav>
          </div> 

</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const dropdownImage = document.getElementById('dropdown-image');
  const dropdown = document.getElementById('dropdown');

  dropdownImage.addEventListener('click', function() {
    // Toggle the visibility of the dropdown content
    if (dropdown.style.display === 'none') {
      dropdown.style.display = 'block';
    } else {
      dropdown.style.display = 'none';
    }
  });

  // Close the dropdown when the user clicks outside of it
  window.addEventListener('click', function(event) {
    if (!dropdown.contains(event.target) && !dropdownImage.contains(event.target)) {
      dropdown.style.display = 'none';
    }
  });
});
// ...




</script>


    <script src="./dashboard.js"></script>
</html>

