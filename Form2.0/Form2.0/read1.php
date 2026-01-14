<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="cssform2.css">
    <title>DANH SÁCH GIẢNG VIÊN</title>
</head>
<body>
    
    <div class="container ">
    <h1 class="text-center text-danger h1">STAFF LIST</h1>
    <p><button type="button-lg" class="btn btn-outline-dark"> <a href="create.php">ADD STAFFS</a></button></p>

        <table class=" table-bordered table-striped table-hover table caption">
            <thead class="table-info text-center .table-bordered">
                <tr>
                    
                    <th>Staff id </th>
                    <th>Name staff</th>
                    <th>Genderation</th>
                    <th>Education</th>
                    <th>Year of Birth</th>
                    <th>Address</th>
                    <th >Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                //kết nối với database
                include("config.php");
                //lấy try vấn trong CSDL từ bảng nhân viên và lưu vào biến $sql 
                $sql = "SELECT `ID`, `MA_NV`, `TEN_NV`, `GIOI_TINH`, `HOC_VAN`, `NAN_SINH`, `EMAIL`, `DIA_CHI` 
                FROM `nhanvien`;";
                $result = mysqli_query($conn, $sql);// Thực thi câu lệnh SQL và lưu kết quả vào biến $result
                
                if(mysqli_num_rows($result)>0){//kiểm tra dữ liệu  trong CSDL có tồn tại hay không
                    while($row = mysqli_fetch_assoc($result)){//mysqli_fetch_assoc($result): Lấy từng hàng kết quả từ kết quả truy vấn dưới dạng một mảng kết hợp (associative array).
                        //Lấy từng giá trị từ hàng kết quả và gán vào các biến tương ứng:
                        $id = $row["ID"];
                        $manv = $row["MA_NV"];
                        $tennv = $row["TEN_NV"];
                        $gt = $row["GIOI_TINH"];
                        $hv = $row["HOC_VAN"];
                        $ns = $row["NAN_SINH"];
                        $email = $row["EMAIL"];
                        $dc = $row["DIA_CHI"];
                        
                        echo '
                        <tr>
                        
                        <td>'.$manv.'</td>
                        <td class="text-start">'.$tennv.'</td>
                        <td>'.$gt.'</td>
                        <td>'.$hv.'</td>
                        <td>'.$ns.'</td>
                        <td>'.$dc.'</td>
                        <td>'.$email.'</td>
                        <td><button class="btn btn-outline-success"><a href="Fix.php?upd='.$id.'" > UPDATE</a> </button>
                        <button class="btn btn-outline-danger"><a href="Delete.php?del='.$id.'" >DELETE</a></button></td>

                       </tr>';
                    }
                }
                ?>
            </tbody>
            
        </table>
        <div class="mb-3 mt-3">
            <button type="button-lg" class="btn btn-outline-secondary"><a href=" user_page.php ">  Return To User Page </a></button>
            <button type="button-lg" class="btn btn-outline-danger"><a href=" admin_page.php "> Return To Admin Page </a></button>
        </div>
       
    </div>

    
</body>
</html>