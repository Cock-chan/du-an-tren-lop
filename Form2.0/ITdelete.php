<?php
include('config.php');

if(isset($_GET['del1']))
{
    $delid1 = $_GET['del1'];

    $sql = "DELETE FROM `hanghoa` WHERE `ID`='$delid1'";
    $result = mysqli_query($conn,$sql);

    if($result){
        header("Location: item.php");
    }
}
 ?>