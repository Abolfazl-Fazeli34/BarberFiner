
<?php
require_once "../config/db.php";

$stmt = $conn->query("SELECT * FROM suppliers ORDER BY id DESC");
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مدیریت تأمین‌کنندگان</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>مدیریت تأمین‌کنندگان</h2>

<a href="add_supplier.php">افزودن تأمین‌کننده جدید</a>

<table border="1" cellpadding="10">
    <tr>
        <th>نام تأمین‌کننده</th>
        <th>شماره تماس</th>
        <th>آدرس</th>
    </tr>

    <?php foreach ($suppliers as $supplier): ?>
    <tr>
        <td><?= htmlspecialchars($supplier['name']) ?></td>
        <td><?= htmlspecialchars($supplier['phone']) ?></td>
        <td><?= htmlspecialchars($supplier['address']) ?></td>
    </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
