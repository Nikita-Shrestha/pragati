<?php
session_start();
// Include database connection and other necessary files
include('dashboard.php');

// Handle form submission
if (isset($_POST['submit'])) {
    $batch = $_POST['batch'];
    $sem = $_POST['sem'];
    $exam = $_POST['exam'];

    // Create a database connection
    $conn = new mysqli("localhost", "root", "", "pragati");
    if ($conn->connect_errno != 0) {
        die("Connection Error");
    }

    // Check if the entry already exists
    $check = "SELECT * FROM exam_register WHERE batch='".$batch."' AND sem='".$sem."' AND exam='".$exam."'";

    $result = $conn->query($check);

    if ($result->num_rows > 0) {
        echo '<script>alert("Exam already exists")</script>';
        exit;
    } else {
        // Use a prepared statement to prevent SQL injection
        $sql = "INSERT INTO exam_register(batch, sem, exam) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $batch, $sem, $exam);

        if ($stmt->execute()) {
           
            echo '<script>alert("Exam added successfully")</script>';
           
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
$sql = "SELECT * FROM exam_register";
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
    width: 80%;
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
<div class="container-fluid">
    <div class="card">
        <h1>Exam Details</h1>
        <div style="overflow-x:auto;">
            <!-- Modal for adding subjects -->
            <section class="modal hidden">
                <div class="flex">
                  <button class="btn-close"> ⨉ </button>
                </div>
            <div class="scrollable-container">
                <form action="exam.php" class="form" method="post" autocomplete="off">
                    <div class="input-box">
                        <label>Batch</label>
                        <input type="number" name="batch" min="2075" max="2080" required/>
                    </div>

                    <div class="input-box">
                        <label>Semester</label>
                        <input type="number"name="sem"min="1" max="8" required />
                    </div>
                    <div class="input-boxa" >
                    <label for="exam">Select an Exam:</label>
<select id="exam" name="exam">
    <option value="Mid term">Mid term</option>
    <option value="Preboard">Preboard</option>
    <option value="Board">Board</option>
</select>
   
                    </div>

                  
                    <div class="clearfix">
                        <button type="submit" class="signupbtn" name="submit">Add Exam</button>
                    </div>
      
                 </form>
                  
            </div>
              </section>


            <div class="overlay hidden"></div>
            <!-- Button to open the modal -->
            <button type="button" class="button button-open">Add Exam</button>

            <div class="table-container">
                <table>
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Batch</th>
                        <th scope="col">Semester</th>
                        <th scope="col">Exam Name</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $serialNumber=1;
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>" . $serialNumber . "</td>
                            <td>" . $row['batch'] . "</td>
                            <td>" . $row['sem'] . "</td> 
                            <td>" . $row['exam'] . "</td>
                            <td>
                                <form action='edit_exam.php' method='post'>
                                    <input type='hidden' value='" . $row['id'] . "' name='exam_edit'>
                                    <input type='submit' class='edit btn' value='Edit' name='edit'>
                                </form> 
                                </td>
                                <td>
                                <form action='delete_exam.php' method='post' class='delete-form'>  
                                    <input type='hidden' value='" . $row['id'] . "' name='exam_delete'>
                                    <input type='submit' class='delete btn' value='Delete' name='delete'>
                                </form>
                            </td>
                        </tr>
                        ";
                        $serialNumber++;// Increment the serial number for the next row

                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>


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
