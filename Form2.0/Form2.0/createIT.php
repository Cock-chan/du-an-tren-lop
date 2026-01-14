<?php
include('config.php');


$sql1 = "SELECT `LOAI_HANG`, `LOAI_HANG` FROM `phan_loai`";
$sql2 = "SELECT `THUONG_HIEU`, `THUONG_HIEU` FROM `thuong_hieu`";


$result1 = mysqli_query($conn,$sql1);
$result2 = mysqli_query($conn,$sql2);


    if(isset($_POST['them'])){
        $idsp = $_POST['idsp'];
        $tensp = $_POST['tsp'];
        $gia = $_POST['gia'];
        $loai = $_POST['sec'];
        $th = $_POST['trade'];

        $sql3 = "SELECT `MA_HANG` FROM `hanghoa` WHERE MA_HANG = '$idsp'";
        $chk1 = mysqli_query($conn,$sql3);

        if (mysqli_num_rows($chk1) > 0)
        {
            
             echo "DON'T HAVE ID ITEMS!";
        }
        else{
             $sql = "INSERT INTO `hanghoa`(`ID`, `MA_HANG`, `TEN_HANG`, `LOAI_HANG`, `THUONG_HIEU`, `GIA`) 
            VALUES ('','$idsp','$tensp','$loai','$th','$gia')";
            $query = mysqli_query($conn,$sql);
    
            if($query){
                
                header("location: item.php");
            }
            else{
                echo "can't not add add items";
            }
          
        }
          
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THÊM GIẢNG VIÊN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="form3.css">
</head>
<body>
    <div class="container mt-3">
        <h1 class="text-center text-success">ADD ITEMS FORM</h1>
        <form action="createIT.php" method="post">
        <div class="mt-3 mb-3">
            ITEM ID:
            <input type="text" class="form-control" placeholder="enter items id" name="idsp">
        </div>
        <div class="mt-3 mb-3">
            NAME:
            <input type="text" class="form-control" placeholder="enter items name" name="tsp">
        </div>
        <div class="mt-3 mb-3">
            SECTORS:
            <select class="form-select" name="sec">
            <?php foreach ($result1 as $op1) { 
                echo "<option value={$op1["LOAI_HANG"]}>".$op1["LOAI_HANG"]."</option>";
            }
                ?>
            </select>
        </div>
        <div class="mt-3 mb-3">
        TRADEMARK:
            <select class="form-select" name="trade">
            <?php foreach ($result2 as $op2) { 
                echo "<option value={$op2["THUONG_HIEU"]}>".$op2["THUONG_HIEU"]."</option>";
            }
                ?>
            </select>
        </div>
        <div class="mb-3 mt-3">
        PRICE(VNĐ):
            <input type="number" class="form-control" placeholder="enter your PRICE" name="gia">
        </div>
        
        <button type="submit" class="btn btn-primary" name="them">CONFIG</button>
</form>
</div> 
</body>
</html>