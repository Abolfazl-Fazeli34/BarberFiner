<?php
session_start();
require_once "../config/db.php";

// دریافت تنظیمات سایت
$stmt = $conn->query("SELECT * FROM site_settings LIMIT 1");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تنظیمات سایت</title>
</head>
<body>

<h2>تنظیمات سایت</h2>

<?php if ($settings): ?>

<p>نام سایت: <?= htmlspecialchars($settings['site_name'] ?? '') ?></p>

<?php else: ?>

<p>هنوز تنظیماتی ثبت نشده است.</p>

<?php endif; ?>

</body>
</html>