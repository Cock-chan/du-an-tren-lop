<?php
   //kết nối với CSDL
   @include 'config.php';
   //lấy dữ liệu từ form
   session_start();//khởi động hoặc tiếp tục một phiên làm việc, cho phép lưu trữ và truy xuất thông tin người dùng trên nhiều trang.
      // LIÊN KẾT TỚI LOẠI NGƯỜ DÙNG
   if(!isset($_SESSION['admin_name'])){
      header('location:login_form.php');//DẪN ĐẾN TRANG ĐĂNG NHẬP
   }

?>

<!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>admin page</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
      <!-- custom css file link  -->
      <link rel="stylesheet" href="style.css">

   </head>
   <body>
         
      <div class="container">

         <div class="content">
            <h3 >hi, <span>admin</span></h3>
            <h1>welcome <span><?php echo $_SESSION['admin_name'] ?></span></h1>
            <p>this is an admin page</p>
            <a href="login_form.php" class="btn">login</a>
            <a href="register_form.php" class="btn">register</a>
            <a href="logout.php" class="btn">logout</a>
            <button class="  btn-outline-info"><a href="read1.php">View List</a> </button>     
            <button class="  btn-outline-info"><a href="item.php">View Item List</a> </button>    
            <button class="  btn-outline-info"><a href="car.php">View Customer List</button>   
         </div>
         
      </div>

   </body>
   </html>