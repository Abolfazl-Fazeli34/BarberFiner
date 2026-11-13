<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل مدیریت آرایشگاه</title>
    <style>
        body {
            font-family: Tahoma;
            background: #f0f4f8;
            margin: 0;
        }
        header {
            background: #173b65;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .logout {
            display: inline-block;
            background: #d93025;
            color: white;
            padding: 12px 20px;
            margin: 15px;
            border-radius: 8px;
            text-decoration: none;
        }
        .container {
            width: 90%;
            margin: 30px auto;
        }
        .welcome {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .item {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 8px #ddd;
        }
        .item a {
            color: #173b65;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
<header>
    <h1>پنل مدیریت آرایشگاه مردانه</h1>
</header>
<a href="logout.php" class="logout">
    خروج از حساب
</a>
<a href="settings.php">تنظیمات سایت</a>
<div class="container">
    <div class="welcome">
        <h2>
        خوش آمدید
            <?= htmlspecialchars($_SESSION["admin_name"]) ?>
        </h2>
        <p>به بخش مدیریت سایت نوبت‌دهی آرایشگاه خوش آمدید.</p>
    </div>
    <div class="menu">
        <div class="item">
            <h3>مدیریت خدمات</h3>
            <p>مشاهده و مدیریت خدمات آرایشگاه</p>
        </div>
        <div class="item">
            <h3>مدیریت آرایشگران</h3>
            <p>مشاهده آرایشگران</p>
        </div>
        <div class="item">
            <h3>مدیریت نوبت‌ها</h3>
            <p>مشاهده نوبت‌های مشتریان</p>
        </div>
        <div class="item">
            <h3>مدیریت پرداخت‌ها</h3>
            <p>مشاهده وضعیت پرداخت‌ها</p>
        </div>
    </div>
</div>
</body>
</html>
