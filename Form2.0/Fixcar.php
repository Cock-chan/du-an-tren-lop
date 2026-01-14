<?php
include('config.php');

$macar = $tencar = $giacar  = '';
    if (isset($_GET['upd3'])) {
    $upid2 = $_GET['upd3'];
        
    $sql3 = "SELECT * FROM `oto` WHERE `ID` = '$upid2'";
    $result4 = mysqli_query($conn, $sql3);
    $row = mysqli_fetch_assoc($result4);

   
    $macar=$row["MA_OTO"];
    $tencar=$row["TEN_OTO"];
    $loai=$row["LOAI_OTO"];
    $giacar=$row["GIA"];

    if(isset($_POST['FIXcar']))
    {

    // $id = $_GET["upd"];  
    // $manv = $tennv = $ns = $email = $dc = '';
    $macar = $_POST['idcar'];
    $tencar = $_POST['tcar'];
    $giacar = $_POST['giacar'];
    

    $sql5 = "UPDATE `oto` SET `MA_OTO`='$macar',`TEN_OTO`='$tencar',`GIA`='$giacar'
         WHERE `ID`='$upid2'";
        $result5 = mysqli_query($conn,$sql5);

        if($result5)
        {
            echo "<script>alert('Sửa CAR thành công!')</script>";
            header("Location: car.php");
        }
        else   
        {
            echo "Error updating record: " . mysqli_error($conn);
        }

}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="cssform2.css">
</head>
<body>
    <div class="container mt-3">
        <h1 class="text-center text-success">FIX CUSTOMER FORM</h1>
        <form action="" method="post"> 
        <!-- bỏ action đi -->
        <div class="mb-3 mt-3">
            <label for="text" class="form-label">CUSTOMER ID:</label>
            <input type="text" class="form-control" name="idcar" value="<?php echo $macar; ?>" >
        </div>
        <div class="mb-3">
           <label for="email" class="form-label">  NAME:</label>
            <input type="text" class="form-control" name="tcar" value="<?php echo $tencar; ?>">
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">  NUMBER :</label>
            <input type="number" class="form-control" name="giacar"  value="<?php echo $giacar; ?>">
        </div>
          
        <input type="submit" class="btn btn-outline-primary" name="FIXcar" value="Sửa">
            <a class="btn btn-outline-warning" href="read1.php">RETURN</a>

</form>
</div> 
</body>
</html>