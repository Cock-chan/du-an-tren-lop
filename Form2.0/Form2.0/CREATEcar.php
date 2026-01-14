<?php
 include('config.php');
 

    $sql0 = "SELECT `LOAI_OTO`, `LOAI_OTO` FROM `loai_oto`";
    $sql9 = "SELECT `THUONG_HIEUOTO`, `THUONG_HIEUOTO` FROM `oto_th`";
    $result1 = mysqli_query($conn, $sql0);
    $result2 = mysqli_query($conn, $sql9);



         if(isset($_POST['adcar'])){
            $idxe = $_POST['idxe'];
            $tenxe = $_POST['txe'];
            $giaxe = $_POST['giaxe'];
            $loai = $_POST['xe'];
            $th = $_POST['thxe'];

            $sql3 = "SELECT  `MA_OTO` FROM `oto` WHERE MA_OTO = '$idxe'";
            $chk3 = mysqli_query($conn,$sql3);

            if (mysqli_num_rows($chk3) > 0)
            {
                
                 echo "DON'T HAVE ID CAR!";
            }
            else{
                 $sql = "INSERT INTO `oto`(`ID`, `MA_OTO`, `TEN_OTO`, `LOAI_OTO`, `THUONG_HIEUOTO` , `GIA` ) 
                VALUES ('','$idxe','$tenxe','$loai','$th','$giaxe')";
                $query = mysqli_query($conn,$sql);
        
                if($query){
                    
                   header("Location:car.php");
                }
                else{
                    echo "can't not add add CAR"; 
                }
            
            }
            
        }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THÊM XE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="form3.css">
</head>
<body>
        
    <div class="container mt-3">
        <h1 class="text-center text-success">ADD CUSTOMER FORM</h1>
        <form action="CREATEcar.php" method="post">
        <div class="mt-3 mb-3">
        CUSTOMER ID:
            <input type="text" class="form-control" placeholder="enter CUSTOMER id" name="idxe">
        </div>
        <div class="mt-3 mb-3">
        CUSTOMER NAME:
            <input type="text" class="form-control" placeholder="enter CUSTOMER name" name="txe">
        </div>
        <div class="mt-3 mb-3">
           GENDERATION:
            <select class="form-select" name="xe">
            <?php foreach ($result1 as $op1) { 
                echo "<option value={$op1["LOAI_OTO"]}>".$op1["LOAI_OTO"]."</option>";
            }
                ?>
            </select>
        </div>
        <div class="mt-3 mb-3">
           MEMBERSHIP:
            <select class="form-select" name="thxe">
            <?php foreach ($result2 as $op2) { 
                echo "<option value={$op2["THUONG_HIEUOTO"]}>".$op2["THUONG_HIEUOTO"]."</option>";
            }
                ?>
            </select>
        </div>
        
        <div class="mb-3 mt-3">
        NUMBER:
            <input type="number" class="form-control" placeholder="enter CUSTOMER number" name="giaxe">
        </div>
        
        <button type="submit" class="btn btn-primary" name="adcar">CONFIG</button>
            </form>
    </div> 
</body>
</html>