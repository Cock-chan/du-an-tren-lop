<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="items.css">
    <title>DANH SÁCH GIẢNG VIÊN</title>
</head>
<body>
    
    <div class="container">
    <h1 class="text-center text-danger">ITEM LIST</h1>
    <p><button type="button-lg" class="btn btn-outline-dark button"> <a href="createIT.php">ADD ITEM</a></button></p>
        <table class=" table-bordered table-striped table-hover">
            <thead class="table-info text-center">
                <tr>
                    <th>ITEM ID</th>
                    <th>ITEM NAME</th>
                    <th>SECTORS</th>
                    <th>TRADEMARK</th>
                    <th>PRICE(VNĐ)</th>
                    <th>ACTIOM</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                include("config.php");

                $sql = "SELECT `ID`, `MA_HANG`, `TEN_HANG`, `LOAI_HANG`, `THUONG_HIEU`, `GIA` FROM `hanghoa` ;";
                $result = mysqli_query($conn, $sql);
                
                if(mysqli_num_rows($result)>0){
                    while($row = mysqli_fetch_assoc($result)){
                        $id=$row["ID"];
                        $masp=$row["MA_HANG"];
                        $tensp=$row["TEN_HANG"];
                        $loai=$row["LOAI_HANG"];
                        $th=$row["THUONG_HIEU"];
                        $gia=$row["GIA"];
                        
                        
                        echo '
                        <tr>
                        
                        <td>'.$masp.'</td>
                        <td class="text-start">'.$tensp.'</td>
                        <td>'.$loai.'</td>
                        <td>'.$th.'</td>
                        <td>'.$gia.'</td>
                        
                        <td><button class="btn btn-outline-success button"><a href="ITfix.php?upd1='.$id.'" > UPDATE</a> </button>
                        <button class="btn btn-outline-danger button"><a href="ITdelete.php?del1='.$id.'" >DELETE</a></button></td>

                       </tr>';
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="mb-3 mt-3">
            <button type="button-lg" class="btn btn-outline-secondary button" ><a href=" user_page.php ">  Return To User Page </a></button>
            <button type="button-lg" class="btn btn-outline-danger button"><a href=" admin_page.php "> Return To Admin Page </a></button>
        </div>
    </div>

    
</body>
</html>