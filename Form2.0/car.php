<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Car.css">
    <title></title>
</head>
<body>
    
    <div class="container">
    <h1 class="text-center text-danger">CUSTOMER LIST</h1>
    <p><button type="button-lg" class="btn btn-outline-dark"> <a href="CREATEcar.php">ADD CUSTOMER</a></button></p>
        <table class=" table-bordered table-striped table-hover">
            <thead class="table-info text-center">
                <tr>
                    
                    <th>CUSTOMER id </th>
                    <th>Name CUSTOMER</th>
                    <th>GENDERATION</th>
                    <th>NUMBER</th>
                    <th>MEMBERSHIP</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                include("config.php");

                $sql = "SELECT `ID`, `MA_OTO`, `TEN_OTO`, `LOAI_OTO`, `GIA`, `THUONG_HIEUOTO`  FROM `oto` ;";
                $result = mysqli_query($conn, $sql);
                
                if(mysqli_num_rows($result)>0){
                    while($row = mysqli_fetch_assoc($result)){
                        $id = $row["ID"];
                        $idcar = $row["MA_OTO"];
                        $namecar = $row["TEN_OTO"];
                        $loai = $row["LOAI_OTO"];
                        $th = $row["THUONG_HIEUOTO"];
                        $gia = $row["GIA"];
                        
                        echo '
                        <tr>
                        
                        <td>'.$idcar.'</td>
                        <td class="text-start">'.$namecar.'</td>
                        <td>'.$loai.'</td>
                        <td>'.$gia.'</td>
                        <td>'.$th.'</td>
                        <td><button class="btn btn-outline-success"><a href="Fixcar.php?upd3='.$id.'" > UPDATE</a> </button>
                        <button class="btn btn-outline-danger"><a href="Deletecar.php?del3='.$id.'" >DELETE</a></button></td>   
                       </tr>';
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="mb-3 mt-3">
            <button type="button-lg" class="btn btn-outline-info"><a href=" user_page.php ">  Return To User Page </a></button>
            <button type="button-lg" class="btn btn-outline-danger"><a href=" admin_page.php "> Return To Admin Page </a></button>
        </div>
    </div>

    
</body>
</html>