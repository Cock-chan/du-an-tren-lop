<?php
// kết nối với database
@include 'config.php';

// lấy dữ liệu từ form
if(isset($_POST['submit'])){//sự kiện xẩy ra nếu người dùng ấn nút submit

   $name = mysqli_real_escape_string($conn, $_POST['name']);// mysql_real_escape_string() để loại bỏ những kí tự có thể gây ảnh hưởng đến câu lệnh SQL
   $email = mysqli_real_escape_string($conn, $_POST['email']);// mysql_real_escape_string() để loại bỏ những kí tự có thể gây ảnh hưởng đến câu lệnh SQL
   $pass = ($_POST['password']);//lấy mật khẩu 
   $cpass = ($_POST['cpassword']);//    lấy xác nhận mật khẩu
   $user_type = $_POST['user_type'];//lấy loại người dùng
    //$_POST là một mảng (array) có các khóa là tên của các trường trong biểu
    //
   $select = " SELECT * FROM user_form WHERE email = '$email' && password = '$pass' "; // Câu lệnh SQL để kiểm tra xem email đã tồn tại chưa
   $result = mysqli_query($conn, $select); // Thực thi câu lệnh SQL và lưu kết quả vào biến $result
   //
   if(mysqli_num_rows($result) > 0){// Kiểm tra xem có người dùng nào với email đã tồn tại chưa

      $error[] = 'user already exist!';  // Nếu có người dùng tồn tại với email này, thêm thông báo lỗi vào mảng $error

   }else{

      if($pass != $cpass){ // Kiểm tra xem mật khẩu và mật khẩu xác nhận có khớp nhau không
         $error[] = 'password not matched!';// Nếu không khớp, thêm thông báo lỗi vào mảng $error
      }else{
        // Câu lệnh SQL để thêm người dùng mới vào cơ sở dữ liệu
         $insert = "INSERT INTO user_form(name, email, password, user_type) VALUES('$name','$email','$pass','$user_type')";
         mysqli_query($conn, $insert);// Thực thi câu lệnh SQL để chèn dữ liệu vào cơ sở dữ liệu
         header('location:login_form.php');// đưa người dùng tới trang đăng nhập
      }
   }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h3>Register Form</h3>
            <label for="name">User Name:</label>
            <input type="text" id="name" name="name" required placeholder="Enter your name">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">

            <label for="cpassword">Confirm Password:</label>
            <input type="password" id="cpassword" name="cpassword" required placeholder="Confirm your password">

            <label for="user_type">User Type:</label>
            <select name="user_type" id="user_type">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <input type="submit" name="submit" value="Register Now" class="btn btn-danger">
            <p>Already have an account? <a href="login_form.php">Login here</a></p>
        </form>
            
        
    </div>
</body>
</html>
