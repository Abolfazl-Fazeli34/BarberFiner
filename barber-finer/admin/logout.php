<?php
session_start();
// پاک کردن اطلاعات ورود مدیر
$_SESSION = array();
// حذف نشست کاربر
session_destroy();
// انتقال به صفحه ورود
header("Location: login.php");
exit;
?>
