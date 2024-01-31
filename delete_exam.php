
<?php
     if(isset($_POST['delete'])){           
     $connection=new mysqli("localhost","root","","pragati");
    if($connection->connect_errno!=0)
     {
         die("Connection Error".$connection->connect_errno);
     }
     $id=$_POST['exam_delete'];
     $sql="DELETE FROM exam_register WHERE id='$id'";
     $result =$connection->query($sql);
     if ($result) {
      
         echo ("<script> alert('Information Deleted Successfully');</script>");
         header("location:exam.php");
     } else {
         die(mysqli_error($connection));
    }  
}     
?>