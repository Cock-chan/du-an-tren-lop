<?php
    //két nối với database
    @include 'config.php';

    session_start();//bắt đầu phiên hoặc tiếp tục phiên trước đó
    session_unset();//Xóa tất cả các biến session hiện tại. Điều này chỉ xóa các biến phiên, nhưng không hủy phiên làm việc.
    session_destroy();//Hủy phiên làm việc hiện tại. Điều này sẽ xóa tất cả dữ liệu phiên và kết thúc phiên làm việc.

    header('location:login_form.php');// dẫn người dùng tới trang đăng nhập

?>