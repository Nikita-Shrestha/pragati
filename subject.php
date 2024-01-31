<?php
session_start();
// Include database connection and other necessary files
include('dashboard.php');

// Handle form submission
if (isset($_POST['submit'])) {
    $sem_id = $_POST['sem_id'];
    $sname = $_POST['sname'];
    $code = $_POST['code'];
    $cr_hrs = $_POST['cr_hrs'];

    // Create a database connection
    $conn = new mysqli("localhost", "root", "", "pragati");
    if ($conn->connect_errno != 0) {
        die("Connection Error");
    }
// Check if the entry already exists
$check = "SELECT * FROM subject WHERE sname='".$sname."' AND code='".$code."'";

$result = $conn->query($check);

if ($result->num_rows > 0) {
    echo '<script>alert("Subject already registered")</script>';
    exit;
}else{
    // Use a prepared statement to prevent SQL injection
    $sql = "INSERT INTO subject(sem_id, sname, code, cr_hrs) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $sem_id, $sname, $code, $cr_hrs);

    if ($stmt->execute()) {
        echo '<script>alert("Subject added successfully")</script>';
        
        exit;
    } else {
        echo("Error");
    }
}
    
}

// Retrieve subject data from the database
$conn = new mysqli("localhost", "root", "", "pragati");
if ($conn->connect_error) {
    die("Connection error");
}


// Define a variable to store the search query
$searchQuery = "";

// Check if a search query is submitted
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

// Modify your SQL query to filter based on the search query
$sql = "SELECT * FROM subject WHERE 
    (sem_id LIKE '%$searchQuery%' OR
    sname LIKE '%$searchQuery%' OR
    code LIKE '%$searchQuery%' OR
    cr_hrs LIKE'%$searchQuery%' )";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Include an external CSS file for styling -->
    <link rel="stylesheet" href="subject.css">
    <style>
        /* Styles for the search icon */
.search-icon {
    position: absolute;
    top: 49%;
    right: 320px; /* Adjust the left position to place it near the search box */
    transform: translateY(-50%);
    cursor: pointer;
    fill: #333; /* Darker color */
    transition: fill 0.2s;
}

/* Styles for the search container */
.search-container {
    position: relative;
    text-align: center;
    margin: 20px auto;
}

#search-input {
    padding: 10px 30px 15px 30px; /* Adjust padding to make space for the icon */
    width: 500px;
    border: 1px solid #ccc;
    border-radius: 10px;
}


        </style>
</head>
<body>
<div class="container-fluid">
    <div class="card">
    <div class="search-container">
    <input type="text" id="search-input" placeholder="Search by semester..."autocomplete="off">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#333" class="search-icon">
        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.50 16 5.92 13.08 3 9.50 3S3 5.92 3 9.50 5.92 16 9.50 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L19.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.50S7.01 5 9.50 5 14 7.01 14 9.50 11.99 14 9.50 14z"/>
        <path d="M0 0h24v24H0z" fill="none"/>
    </svg>
</div>
        <h1>Subject Details</h1>
        <div style="overflow-x:auto;">
     
            <!-- Modal for adding subjects -->
            <section class="modal hidden">
         
                <div class="flex">
                  <button class="btn-close"> ⨉ </button>
                </div>
            <div class="scrollable-container">
                <form action="subject.php" class="form" method="post" autocomplete="off">
                    <div class="input-box">
                        <label>Sem_ID</label>
                        <input type="text" name="sem_id" required />
                    </div>

                    <div class="input-box">
                        <label>Subject Name</label>
                        <input type="text"name="sname" required />
                    </div>
                    <div class="input-box address" >
                        <label>Subject Code</label>
                        <input type="text"  name="code" pattern="^[a-zA-Z0-9\s]*$" required />   
                    </div>
                     <div class="input-box">
                        <label>Credit Hours</label>
                        <input type="number"name="cr_hrs"  required />
                    </div>
                  
                    <div class="clearfix">
                        <button type="submit" class="signupbtn" name="submit">Add Subject</button>
                    </div>
      
                 </form>
                  
            </div>
              </section>


            <div class="overlay hidden"></div>
            <!-- Button to open the modal -->
            <button type="button" class="button button-open">Add Subject</button>

            <div class="table-container">
                <table>
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Sem_ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Code</th>
                        <th scope="col">Credit Hours</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    // Fetch and display subjects sorted by sem_id and sname
$sql = "SELECT * FROM subject ORDER BY sem_id ASC, sname ASC";
$result = $conn->query($sql);
$serialNumber = 1; // Initialize the serial number
$currentSemester = null; // Initialize the current semester

while ($row = $result->fetch_assoc()) {
    $semester = $row['sem_id'];

    // If a new semester is encountered, display a header row
    if ($semester !== $currentSemester) {
        echo "<tr><td colspan='7'><h2>Semester $semester</h2></td></tr>";
        $currentSemester = $semester;
    }

    // Display subject details here
    echo "<tr>";
    echo "<td>" . $serialNumber . "</td>";
    echo "<td>" . $row['sem_id'] . "</td>";
    echo "<td>" . $row['sname'] . "</td>";
    echo "<td>" . $row['code'] . "</td>";
    echo "<td>" . $row['cr_hrs'] . "</td>";
    echo "<td>
            <form action='edit.php' method='post'>
                <input type='hidden' value='" . $row['id'] . "' name='subject_edit'>
                <input type='submit' class='edit btn' value='Edit' name='edit'>
            </form>
          </td>";
    echo "<td>
            <form action='delete_subject.php' method='post' class='delete-form'>  
                <input type='hidden' value='" . $row['id'] . "' name='subject_delete'>
                <input type='submit' class='delete btn' value='Delete' name='delete'>
            </form>
          </td>";
    echo "</tr>";

    $serialNumber++; // Increment the serial number for the next row
}
?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>

    // Function to filter the table based on the search input
    document.querySelector(".search-icon").addEventListener("click", function () {
        document.getElementById("search-input").focus();
    });
    document.getElementById("search-input").addEventListener("input", function () {
    var searchInput = this.value.toLowerCase();
    var table = document.querySelector("table");
    var rows = table.querySelectorAll("tbody tr");

    rows.forEach(function (row) {
        var semCell = row.querySelector("td:nth-child(2)"); // Cell for sem_id
        var snameCell = row.querySelector("td:nth-child(3)"); // Cell for sname

        if (semCell && snameCell) {
            var semValue = semCell.textContent.toLowerCase();
            var snameValue = snameCell.textContent.toLowerCase();

            if (semValue.includes(searchInput) || snameValue.includes(searchInput)) {
                row.style.display = ""; // Show the row
            } else {
                row.style.display = "none"; // Hide the row
            }
        }
    });
});




  const modal = document.querySelector(".modal");
const overlay = document.querySelector(".overlay");
const openModalBtn = document.querySelector(".button-open");
const closeModalBtn = document.querySelector(".btn-close");


const openModal = function () {
  modal.classList.remove("hidden");
  overlay.classList.remove("hidden");
};
openModalBtn.addEventListener("click", openModal);
const closeModal = function () {
  modal.classList.add("hidden");
  overlay.classList.add("hidden");
};
closeModalBtn.addEventListener("click", closeModal);
overlay.addEventListener("click", closeModal);    
  </script>

<script>
    // JavaScript code for form submission
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
</body>
</html>
