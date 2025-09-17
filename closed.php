<?php
session_start();
if (!isset($_SESSION["emp_id"]) || !isset($_SESSION['email'])) {
  echo "<script>
            alert('Please login first!');
            window.location.href='login.php';
          </script>";
  exit();
}
include"connection.php";
$obj= new connection ();
$connect = $obj->connect() ;

$id = $_GET["id"];
$select =mysqli_query($connect,"SELECT * FROM customers WHERE id='$id'");
$row=mysqli_fetch_assoc($select);
if($row['status']!='0'){
    $update = mysqli_query($connect,"UPDATE customers SET status='0' ,closed_at= NOW() WHERE id='$id'");
        echo "<script>alert('Closed successfully!'); window.location.href='customers-list.php';</script>";

}
else{
    echo "<script>alert('Already closed!'); window.location.href='customers-list.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>