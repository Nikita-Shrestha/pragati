
<?php
     if(isset($_POST['delete'])){           
     $connection=new mysqli("localhost","root","","pragati");
    if($connection->connect_errno!=0)
     {
         die("Connection Error".$connection->connect_errno);
     }
     $id=$_POST['mark_delete'];
     $sql="DELETE FROM marks WHERE id='$id'";
     $result =$connection->query($sql);
     if ($result) {
      
         echo ("<script> alert('Information Deleted Successfully');</script>");
         header("location:marks.php");
     } else {
         die(mysqli_error($connection));
    }  
}     
?>