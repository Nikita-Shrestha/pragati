<?php
include('dashboard.php');
$conn = new mysqli("localhost", "root", "", "pragati");
if ($conn->connect_error) {
    die("Connection error");
}

// Fetch student data along with unique semesters
$sql = "SELECT s.*, GROUP_CONCAT(DISTINCT m.sem ORDER BY m.sem) AS semesters FROM sregister s LEFT JOIN marks m ON s.id = m.student_id WHERE s.status='enrolled' GROUP BY s.id ORDER BY s.id DESC";
$result = $conn->query($sql);
$students = [];

while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

// Encode the student data as JSON for JavaScript
$studentsJson = json_encode($students);

// Pass the student data and semesters to JavaScript
echo "<script>var studentsData = $studentsJson;</script>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<style>
   table {
    width: 100%; /* Adjust the width as needed */
            margin: auto; /* Center the table */
            border-collapse: collapse;
  }

th, td {
   text-align: left;
   padding: 12px;
   border-bottom: 1px solid #ddd; /* Add borders between rows */
}
tr:hover {
      background-color:#e7e7e7;
    }

.view{
  cursor:pointer;
  background-color: #4CAF50;
  border:none;
  border-radius: 5px;
  color: white;
 padding: 12px 12px; 
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px; 
   box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24),0 17px 50px 0 rgba(0,0,0,0.19);
}

.scrollable-container {
  
  overflow-y: auto; /* Add a vertical scrollbar when content overflows */
  padding: 10px; /* Add some padding for spacing */
}
.table-container {
        max-height: 600px; /* Set the maximum height for the container */
        overflow-y: auto; /* Enable vertical scrollbar when content exceeds the height */
       
 margin-top: 20px; /* Optional: Add margin for spacing */
    }  
  
    .view:hover{
            background-color: #45a049;
        }

 /* Style the select boxes */
 select {
        padding: 7px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    /* Style the dropdown options */
    select option {
        background-color: #fff;
        color: #333;
    }
 
    .container-fluid {
            padding: 10px;
            margin: 10px auto; /* Center the container horizontally */
             /* Set a maximum width if desired */
        }
        .modal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1;
    padding: 20px;
    background-color: #fefefe;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.modal-content {
    max-width: 300px;
    margin: 0 auto;
}
.close {
    color: #aaa;
    float: right;
    font-size: 20px;
    font-weight: bold;
    cursor: pointer;
}


.close:hover,
.close:focus {
    color: #000;
}
/* Add this to your existing CSS styles */
button.pop {
    animation: pop 0.5s ease-out;
}

@keyframes pop {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
    }
}

        
/* Style for the semester modal */
#semesterModal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1;
    padding: 20px;
    background-color: #fefefe;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}

#semesterModal .modal-content {
    max-width: 300px;
    margin: 0 auto;
}

#semesterModal .close {
    color: #aaa;
    float: right;
    font-size: 20px;
    font-weight: bold;
    cursor: pointer;
}

#semesterModal .close:hover,
#semesterModal .close:focus {
    color: #000;
}

#semesterModal button {
    display: block;
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

#semesterModal button:hover {
    background-color: #45a049;
}

</style>

</head>
<body>
<div class="container-fluid">
    <div class="card">
      
         <div style="overflow-x:auto;">    
         <label for="semester-filter">Filter by Semester:</label>
<select id="data-semester">
    <option value="">All</option>
    <option value="1">Semester 1</option>
    <option value="2">Semester 2</option>
    <option value="3">Semester 3</option>
    <option value="4">Semester 4</option>
    <option value="5">Semester 5</option>
    <option value="6">Semester 6</option>
    <option value="7">Semester 7</option>
    <option value="8">Semester 8</option>
    <!-- Add more options as needed -->
</select>

<label for="batch-filter">Filter by Batch:</label>
<select id="data-batch">
    <option value="">All</option>
    <option value="2075">Batch 2075</option>
    <option value="2076">Batch 2076</option>
    <option value="2077">Batch 2077</option>
    <option value="2078">Batch 2078</option>
    <option value="2079">Batch 2079</option>
    <option value="2080">Batch 2080</option>
    <!-- Add more options as needed -->
</select>

              <div class="table-container">
                <table>
                  <thead>
                    <tr>
                      <th scope="col">SN</th>
                      <th scope="col">Name</th>
                      <th scope="col">Batch</th>
                      <th scope="col">Semester</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php
                      $conn= new mysqli("localhost","root","","pragati");
                      if($conn->connect_error)
                      {
                      die("Connection error");
                      }
                      $sql = "SELECT * from sregister where status='enrolled' ORDER BY id DESC";
                      $result=$conn->query($sql);
                      $serialNumber=1;// Initialize the serial number
                     
                      while($row=$result->fetch_assoc())
                      {
                        $studentId = $row['id'];
                      echo "
                      <tr>
                      <td>".$serialNumber."</td>
                      <td>".$row['name']."</td>
                      <td data-batch='".$row['batch']."'>".$row['batch']."</td> 
                      <td data-semester='".$row['sem']."'>".$row['sem']."</td>
                      <td>
                      <form action='reportview.php' method='get'>
                <input type='hidden' value='".$row['id']."' name='report_view'>
                <input type='button' class='view btn' value='View' name='view' data-student-id='".$row['id']."'>
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

  <div>
  <div id="semesterModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeSemesterModal()">&times;</span>
            <p>Choose a Semester:</p>
            <!-- Add your semester options here -->
            <button onclick="selectSemester(1)">Semester 1</button>
            <button onclick="selectSemester(2)">Semester 2</button>
            <button onclick="selectSemester(3)">Semester 3</button>
            <button onclick="selectSemester(4)">Semester 4</button>
            <button onclick="selectSemester(5)">Semester 5</button>
            <button onclick="selectSemester(6)">Semester 6</button>
            <button onclick="selectSemester(7)">Semester 7</button>
            <button onclick="selectSemester(8)">Semester 8</button>
            <!-- Add more buttons for other semesters -->
        </div>
    </div>
    <script>
         var modal; // Add this line at the beginning of your script
     var currentStudentId;
   
    function closeSemesterModal() {
            var semesterModal = document.getElementById('semesterModal');
            if (semesterModal) {
                semesterModal.style.display = 'none';
            }
        }

        function openModal(studentId, semester) {
    console.log(`Opening modal for student ${studentId}, semester ${semester}`);
    modal = document.getElementById("marksheetModal");
    var iframe = document.getElementById("reportFrame");

    if (iframe && modal) {
        try {
            iframe.onload = function () {
                console.log("Iframe content loaded");
                adjustModalSize();
            };

            iframe.src = `reportview.php?report_view=${studentId}&semester=${semester}`;

            // Reset modal size to auto before adjusting based on content
            modal.style.width = "auto";
            modal.style.height = "auto";

            modal.style.display = "block"; // Ensure the modal is displayed
        } catch (error) {
            console.error("Error loading content into iframe:", error);
        }
    }
}

function adjustModalSize() {
    var modal = document.getElementById("marksheetModal");
    var iframe = document.getElementById("reportFrame");

    if (modal && iframe) {
        // Set the modal size based on the content size
        var contentWidth = iframe.contentWindow.document.documentElement.scrollWidth;
        var contentHeight = iframe.contentWindow.document.documentElement.scrollHeight;

        console.log("Content size:", contentWidth, "x", contentHeight);

        // Add some padding or margin if needed
        var padding = 20;

        modal.style.width = contentWidth + padding + "px";
        modal.style.height = contentHeight + padding + "px";
    }
}

function closeModal() {
    var modal = document.getElementById("marksheetModal");
    var iframe = document.getElementById("reportFrame");

    if (modal && iframe) {
        iframe.src = "";
        modal.style.display = "none";
    }
}
function selectSemester(studentId, semester) {
    console.log("Selected semester:", semester);
    console.log("Selected studentId:", studentId);

        // Redirect to reportview.php with student_id and semester as query parameters
        window.location.href = `reportview.php?report_view=${studentId}&semester=${semester}`;

    closeSemesterModal();

    // Open the modal
    openModal(currentStudentId, semester);
}

document.addEventListener("DOMContentLoaded", function () {
    var viewButtons = document.querySelectorAll('.view.btn');
    var semesterModal = document.getElementById('semesterModal');

    var semestersData = {};

    studentsData.forEach(function (student) {
        var semesters = student.semesters || '';
        semestersData[student.id] = semesters.split(',').map(Number);
    });

    window.semestersData = semestersData;

    function openSemesterModal() {
        var semesters = semestersData[currentStudentId] || [];

        if (semesters.length > 0) {
            var semesterOptions = semesters.map(function (semester) {
    return `<button onclick="selectSemester('${currentStudentId}', ${semester})">Semester ${semester}</button>`;
}).join('');


            semesterModal.innerHTML = `
                <div class="modal-content">
                    <span class="close" onclick="closeSemesterModal()">&times;</span>
                    <p>Choose a Semester:</p>
                    ${semesterOptions}
                </div>
            `;
            semesterModal.style.display = 'block';
        } else {
            semesterModal.innerHTML = "<p>No recorded semesters for this student.</p>";
            semesterModal.style.display = 'block';
        }
    }

    viewButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            currentStudentId = event.target.getAttribute('data-student-id');
            openSemesterModal();
        });
    });
// Add an event listener to the modal overlay
    var modalOverlay = document.getElementById("marksheetModal");
    if (modalOverlay) {
        modalOverlay.addEventListener("click", function (event) {
            var modalContent = document.getElementById("modalContent");
            if (event.target === modalOverlay && !modalContent.contains(event.target)) {
                closeModal(); // Close the modal if the click is outside the modal content
            }
        });
    }
    
   
});

   
   function filterTable() {
    var semesterFilter = document.getElementById("data-semester").value;
    var batchFilter = document.getElementById("data-batch").value;
    var table = document.querySelector("table");
    var rows = table.querySelectorAll("tbody tr");

    rows.forEach(function(row) {
        var semesterCell = row.querySelector("td[data-semester]");
        var batchCell = row.querySelector("td[data-batch]");
        var semester = semesterCell.getAttribute("data-semester");
        var batch = batchCell.getAttribute("data-batch");

        // Check if the row should be displayed based on filters
        if (
            (semesterFilter === "" || semester === semesterFilter) &&
            (batchFilter === "" || batch === batchFilter)
        ) {
            row.style.display = ""; // Show the row
        } else {
            row.style.display = "none"; // Hide the row
        }
    });
}

// Add event listeners to update the table when the filters change
document.getElementById("data-semester").addEventListener("change", filterTable);
document.getElementById("data-batch").addEventListener("change", filterTable);

// Initial table filtering when the page loads
filterTable();

</script>

<div id="marksheetModal" class="modal">
<!-- Add this to your modal content -->
<div class="modal-content" id="modalContent">
  <span class="close" onclick="closeModal()">&times;</span>
  <iframe id="reportFrame" style="width:100%; height:100%; border:none;"></iframe>
</div>

</div>

</body>
</html>
