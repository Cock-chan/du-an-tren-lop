<?php
//kết nối với database
include('config.php');

$manv = $tennv = $ns = $email = $dc = '';// khởi tạo các biến với giá trị rỗng 
    if (isset($_GET['upd'])) { //sự kiện khi người dùng ấn nút update
    $upid = $_GET['upd']; //gán nút update với biến $upid
        
    $sql3 = "SELECT * FROM `nhanvien` WHERE `ID` = '$upid'";// lấy thông tin của biến $upid
    $result4 = mysqli_query($conn, $sql3);//lưu vào biến 
    $row = mysqli_fetch_assoc($result4);// Thực thi câu lệnh SQL và lưu kết quả vào biến $result
    //mysqli_fetch_assoc($result4): Lấy hàng kết quả đầu tiên từ kết quả truy vấn dưới dạng mảng kết hợp (associative array) và lưu vào biến $row.

    //Lấy các giá trị từ mảng $row và gán chúng vào các biến tương ứng
    $manv = $row["MA_NV"];
    $tennv = $row["TEN_NV"];
    $ns = $row["NAN_SINH"];
    $email = $row["EMAIL"];
    $dc = $row["DIA_CHI"];

    if(isset($_POST['fix']))// sự kiện xẩy ra khi người dùng nút Fix
    {

    //Lấy các giá trị từ biểu mẫu và gán chúng vào các biến tương ứng
    $manv = $_POST['idnv'];
    $tennv = $_POST['tnv'];
    $ns = $_POST['ns'];
    $dc = $_POST['address'];
    $email =$_POST['eml'];
        // Câu lệnh SQL để cập nhật thông tin của nhân viên trong bảng nhanvien với các giá trị mới.
    $sql5 = "UPDATE `nhanvien` SET `MA_NV`='$manv',`TEN_NV`='$tennvn',`NAN_SINH`='$ns',`DIA_CHI`='$dc',`EMAIL`='$email'
         WHERE `ID`='$upid'";
        $result5 = mysqli_query($conn,$sql5);// Thực thi câu lệnh SQL và lưu kết quả vào biến $result

        if($result5)
        {
           // echo "<script>alert('Sửa GV thành công!')</script>";
            header("Location: read1.php");//nếu sủa thành công thì dẫn người dùng tới trang read1.php
        }
        else   
        {
            echo "Error updating record: " . mysqli_error($conn);//nếu sửa thấp bại thì báo lỗi 
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
        <h1 class="text-center text-success">FIX STAFFS FORM</h1>
        <form action="" method="post"> 
        <!-- bỏ action đi -->
        <div class="mb-3 mt-3">
            <label for="text" class="form-label">STAFFS ID:</label>
            <input type="text" class="form-control" name="idnv" value="<?php echo $manv; ?>" >
        </div>
        <div class="mb-3">
           <label for="email" class="form-label"> STAFFS NAME:</label>
            <input type="text" class="form-control" name="tnv" value="<?php echo $tennv; ?>">
        </div>
       <div class="mb-3 mt-3">
           <label for="email" class="form-label"> YEAR OF BIRTH:</label>
            <input type="text" class="form-control" name="ns" value="<?php echo $ns; ?>">
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">  ADDRESS :</label>
            <input type="text" class="form-control" name="address"  value="<?php echo $dc; ?>">
        </div>
        <div class="mb-3">
           <label for="email" class="form-label">EMAIL:</label>
           <input type="email" class="form-control" name="eml" value="<?php echo $email; ?>">

        </div>  
        <input type="submit" class="btn btn-outline-primary" name="fix" value="Sửa">
            <a class="btn btn-outline-warning" href="read1.php">RETURN</a>

</form>
</div> 
</body>
</html>