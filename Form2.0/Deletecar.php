<?php
include('config.php');

if(isset($_GET['del3']))
{
    $delid3 = $_GET['del3'];

    $sql = "DELETE FROM `oto` WHERE `ID`='$delid3'";
    $result = mysqli_query($conn,$sql);

    if($result){
        header("Location: car.php");
    }
}
 ?>