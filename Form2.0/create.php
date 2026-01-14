<?php
include('config.php'); // kết nối với database

// truy vấn và lấy dữ liệu từ bảng giới tính và học vấn ở trong CSDL
$sql1 = "SELECT `GIOI_TINH`, `GIOI_TINH` FROM `gioi_tinh`";//$sql là biến dùng để chứa dữ liệu từ SQL
$sql2 = "SELECT `HOC_VAN`, `HOC_VAN` FROM `hoc_van`";


$result1 = mysqli_query($conn,$sql1);//Truy vấn được thực thi bằng hàm mysqli_query()
$result2 = mysqli_query($conn,$sql2);// kết quả được lưu trong biến $result2    

// thực hiện hành động khi người dùng ấn vào nút submit
    if(isset($_POST['add'])){
        // lấy dữ liệu từ form
        $id = $_POST['nvid']; //các biến $ dùng để lưu trữ dữ liệu nhập vào từ biểu mẫu HTML khi người dùng gửi thông tin
        $name = $_POST['tnv'];
        $gioitinh = $_POST['gen'];
        $hocvan = $_POST['edu'];
        $year = $_POST['ns'];
        $ad = $_POST['address'];
        $email =$_POST['eml'];
        //
        $sql3 = "SELECT `MA_NV` FROM `nhanvien` WHERE MA_NV = '$id'";//lấy giá trị MA_NV từ bảng nhanvien với điều kiện MA_NV bằng giá trị $id.
        $chk = mysqli_query($conn,$sql3);// thực thi câu lệnh SQL trên kết nối cơ sở dữ liệu $conn và lưu kết quả trong biến $chk.

        //kiểm  tra dữ liệu người dùng có tồn tại hay không
        if (mysqli_num_rows($chk) > 0)//mysqli_num_rows($chk) trả về số hàng kết quả của truy vấn. nếu >0 là có dữ liệ người dùng
        {
            
            echo "Mã giảng viên đã tồn tại!";// in ra lỗi 
        }
        else{
            //thực hiện chèn dữ liệu vào database
            //chèn từng dữ liệu của biến tương ứng với tùng bảng trong CSDL
            $sql = "INSERT INTO `nhanvien`(`ID`, `MA_NV`, `TEN_NV`, `GIOI_TINH`, `HOC_VAN`, `NAN_SINH`, `EMAIL`, `DIA_CHI`)  
            VALUES ('','$id','$name','$gioitinh','$hocvan','$year','$ad','$email')";

            $query = mysqli_query($conn,$sql);//lưu trữ bằng biến $query
            
            if($query){//Kiểm tra xem truy vấn chèn dữ liệu có thành công hay không
                
                header("location: read1.php");// dẫn người dùng tới trang xem bảng 
            }
            else{
                echo "can't not add add staff"; //in ra lỗi nếu người dùng nhập sai thông tin 
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
    <div class="container mt-3 ">
        <h1 class="text-center text-success">ADD STAFFS FORM</h1>
        <form action="create.php" method="post">
        <div class="mt-3 mb-3">
            STAFFS ID:
            <input type="text" class="form-control" placeholder="enter staffs id" name="nvid">
        </div>
        <div class="mt-3 mb-3">
            NAME:
            <input type="text" class="form-control" placeholder="enter staffs name" name="tnv">
        </div>
        <div class="mt-3 mb-3">
            Genderation:
            <select class="form-select" name="gen">
            <?php foreach ($result1 as $op1) { 
                echo "<option value={$op1["GIOI_TINH"]}>".$op1["GIOI_TINH"]."</option>";
            }
                ?>
            </select>
        </div>
        <div class="mt-3 mb-3">
            EDUCATION:
            <select class="form-select" name="edu">
            <?php foreach ($result2 as $op2) { 
                echo "<option value={$op2["HOC_VAN"]}>".$op2["HOC_VAN"]."</option>";
            }
                ?>
            </select>
        </div>
        <div class="mb-3 mt-3">
            Year of Birth:
            <input type="text" class="form-control" placeholder="enter your years of birth" name="ns">
        </div>
        <div class="mb-3">
           Address:
            <input type="text" class="form-control" placeholder="enter your address" name="address">
        </div>
        <div class="mb-3">
            EMAIL:
            <input type="email" class="form-control" placeholder="enter your email" name="eml">
        </div>  
        <button type="submit" class="btn btn-primary" name="add">CONFIG</button>
</form>
</div> 
</body>
</html>