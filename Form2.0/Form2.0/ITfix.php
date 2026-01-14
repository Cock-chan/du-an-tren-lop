<?php
include('config.php');

$masp = $tensp = $gia  = '';
    if (isset($_GET['upd1'])) {
    $upid1 = $_GET['upd1'];
        
    $sql3 = "SELECT * FROM `hanghoa` WHERE `ID` = '$upid1'";
    $result4 = mysqli_query($conn, $sql3);
    $row = mysqli_fetch_assoc($result4);

   
    $masp=$row["MA_HANG"];
    $tensp=$row["TEN_HANG"];
    $loai=$row["LOAI_HANG"];
    $th=$row["THUONG_HIEU"];
    $gia=$row["GIA"];

    if(isset($_POST['ITfix']))
    {

    // $id = $_GET["upd"];  
    // $manv = $tennv = $ns = $email = $dc = '';
    $masp = $_POST['idsp'];
    $tensp = $_POST['tsp'];
    $gia = $_POST['gia'];
    

    $sql5 = "UPDATE `hanghoa` SET `MA_HANG`='$masp',`TEN_HANG`='$tensp',`GIA`='$gia'
         WHERE `ID`='$upid1'";
        $result5 = mysqli_query($conn,$sql5);

        if($result5)
        {
            echo "<script>alert('Sửa ITEMs thành công!')</script>";
            header("Location: item.php");
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
        <h1 class="text-center text-success">FIX ITEMS FORM</h1>
        <form action="" method="post"> 
        <!-- bỏ action đi -->
        <div class="mb-3 mt-3">
            <label for="text" class="form-label">ITEMS ID:</label>
            <input type="text" class="form-control" name="idsp" value="<?php echo $masp; ?>" >
        </div>
        <div class="mb-3">
           <label for="text" class="form-label"> ITEMS NAME:</label>
            <input type="text" class="form-control" name="tsp" value="<?php echo $tensp; ?>">
        </div>
        <div class="mb-3">
          <label for="text" class="form-label">  PRICE(VNĐ):</label>
            <input type="number" class="form-control" name="gia"  value="<?php echo $gia; ?>">
        </div>
          
        <input type="submit" class="btn btn-outline-primary" name="ITfix" value="Sửa">
            <a class="btn btn-outline-warning" href="read1.php">RETURN</a>

</form>
</div> 
</body>
</html>