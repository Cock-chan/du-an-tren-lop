<?php
    $sever ="localhost";
    $username ="root";
    $password ="";
    $database ="user_db";
    $conn = mysqli_connect($sever,$username,$password,$database);
    
    // $conn = mysqli_connect("localhost","root","","user_db");
    if (!$conn) 
    {
        die("Connection failed: " . mysqli_connect_error());
    
    }
        //echo "Kết nối thành công";
            

?>