<?php
//kết nối với database
include('config.php');
//gán với nút xóa
if(isset($_GET['del']))//sự kiện xẩy ra khi người dùng ấn nút xóa  truyền qua URL
{
    $delid = $_GET['del'];//gán nút xóa với biến $delid

    //
    $sql = "DELETE FROM `nhanvien` WHERE `ID`='$delid'";//gán Câu lệnh SQL để xóa nhân viên có ID là $delid từ bảng nhanvien. vào biến 

    $result = mysqli_query($conn,$sql);//lưu vào biến 

    if($result){
        header("Location: read1.php");//dưa người dùng tới trang read1.php
    }
}
 ?>