<?php
//két nối với database
include 'config.php';

session_start();//khởi động hoặc tiếp tục một phiên làm việc, cho phép lưu trữ và truy xuất thông tin người dùng trên nhiều trang.

if(isset($_POST['submit'])){ // thực hiện hành động khi người dùng ấn nút submit

   //$name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);// mysql_real_escape_string() để loại bỏ những kí tự có thể gây ảnh hưởng đến câu lệnh SQL
   $pass = ($_POST['password']);//$_POST là một siêu biến toàn cục trong PHP dùng để thu thập dữ liệu từ biểu mẫu HTML gửi đến máy chủ qua phương thức POST.
   //$_POST['password'] lấy dữ liệu từ password trong html
       //$_POST là một mảng (array) có các khóa là tên của các trường trong biểu
   // $cpass = ($_POST['cpassword']);
  //$user_type = $_POST['user_type'];


   // kiểm tra dữ liệu trong database khi người dùng đăng nhập
   $select = " SELECT * FROM user_form WHERE email = '$email' && password = '$pass' "; // Câu lệnh SQL để kiểm tra xem email và password đã tồn tại chưa
   $result = mysqli_query($conn, $select); // Thực thi câu lệnh SQL và lưu kết quả vào biến $result

   //kiểm tra du liệu có tồn tại hay ko
   if(mysqli_num_rows($result) > 0){// trường hợp này là mk có khớp hoay ko

      $row = mysqli_fetch_array($result);

      if($row['user_type'] == 'admin'){// nếu người dùng là admin thì

         $_SESSION['admin_name'] = $row['name'];
         header('location:admin_page.php');// đưa đến trang admin và in tên admin

      }elseif($row['user_type'] == 'user'){//nếu là user

         $_SESSION['user_name'] = $row['name'];
         header('location:user_page.php');//đưa đến trang user và in tên của user

      }
     
   }else{
      $error[] = 'incorrect email or password!';
      echo $error;//in và hiển thị lỗi nếu ko có dữ liệu
   }

};
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login form</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <link rel="stylesheet" href="style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post">
      <h3 style="color: #0000ff;">login now</h3>
         <?php
         if(isset($error)){
            foreach($error as $error){
               echo '<span class="error-msg">'.$error.'</span>';
            };
         };
         ?>
      EMAIL <input type="email" name="email" required placeholder="enter your email">
      PASSWORD<input type="password" name="password" required placeholder="enter your password">
      <input type="submit" name="submit" class="btn btn-block btn-outline-primary btn-center" value="login now" >
      <p>don't have an account? <a href="register_form.php">register now</a></p>
   </form>

</div>

</body>
</html>