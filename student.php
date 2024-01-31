<?php
include('dashboard.php');
function getStatusClass($status) {
  if ($status === 'Enrolled') {
      return 'Enrolled';
  } elseif ($status === 'Pending') {
      return 'Pending';
  } else {
      return ''; // Handle other statuses or errors
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
      h2{
        margin-top:30px;
        margin-left: 30px;
      }
    table{
      width:100%;
  margin-left: 30px;
    }
    th, td{
      text-align: left;
      padding: 15px;
    }
    tr:hover {
      background-color:#e7e7e7;
    }

.update{
  cursor:pointer;
  background-color: #4CAF50;
  border:none;
  border-radius: 5px;
  color: white;
padding: 10px 15px; 
margin-right: 0px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px; 
   box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24),0 17px 50px 0 rgba(0,0,0,0.19);
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

.upgrade{
  cursor:pointer;
  background-color: #0530ad;
  border:none;
  border-radius: 4px;
  color: white;
  padding: 8px 15px; 
  margin-right: 0px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 15px; 
   box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24),0 17px 50px 0 rgba(0,0,0,0.19);
}

.scrollable-container {
  max-height: 400px; /* Set the maximum height to your desired value */
  overflow-y: auto; /* Add a vertical scrollbar when content overflows */
  padding: 10px; /* Add some padding for spacing */
}

.Enrolled{
    color: green;
    /* Additional styling for approved status here */
}

.Pending {
    color: red;
    /* Additional styling for pending status here */
}
.table-container {
        max-height: 500px; /* Set the maximum height for the container */
        overflow-y: auto; /* Enable vertical scrollbar when content exceeds the height */
       
        margin-top: 20px; /* Optional: Add margin for spacing */
    }  
  
    .verify:hover, .view:hover{
            background-color: #45a049;
        }
        .upgrade:hover {
    background-color: #002080;
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

</style>
</head>
<body>
<div class="container-fluid">
    <div class="card">
      <h2>Student Details</h2>
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
                      <th scope="col">Email</th>
                      <th scope="col">Contact</th>
                      <th scope="col">Batch</th>
                      <th scope="col">Semester</th>
                      <th scope="col">Status</th>
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
                      echo "
                      <tr>
                      <td>".$serialNumber."</td>
                      <td>".$row['name']."</td>
                      <td>".$row['email']."</td>
                      <td>".$row['contact']."</td>
                      <td data-batch='".$row['batch']."'>".$row['batch']."</td> 
                      <td data-semester='".$row['sem']."'>".$row['sem']."</td>
                      <td class='status-cell " . getStatusClass($row['status']) . "'>" . $row['status'] . "</td>

                      <td>

                      <form action='studentupdate.php' method='post'>
                 <input type='hidden' value='".$row['id']."' name='student_update'>
                 <input type='submit' class='update btn' value = 'Update' name='update'>
                 </form> 
                 </td>
                 
    
                 <td>
                 <form action='upgrade.php' method='post'>
                 
                 <input type='hidden' value='".$row['id']."'name='student_upgrade'>
 
                 <input type='submit' class='upgrade btn' value = 'Upgrade' name='upgrade'>
                 </form>
                 </td>
                 <td>
                 <form action='view.php' method='post'>
                 
                 <input type='hidden' value='".$row['id']."'name='student_view'>
 
                 <input type='submit' class='view btn' value = 'View' name='view'>
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
<!-- ... Previous HTML code ... -->
<script>
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


</body>
</html>
