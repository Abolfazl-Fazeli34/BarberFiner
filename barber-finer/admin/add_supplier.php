
<?php
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $description = trim($_POST["description"]);

    $stmt = $conn->prepare(
        "INSERT INTO suppliers (name, phone, address, description)
         VALUES (?, ?, ?, ?)"
    );

    $stmt->execute([$name, $phone, $address, $description]);

    header("Location: suppliers.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>افزودن تأمین‌کننده</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>افزودن تأمین‌کننده</h2>

<form method="POST">

    <label>نام تأمین‌کننده:</label>
    <input type="text" name="name" required>

    <br><br>

    <label>شماره تماس:</label>
    <input type="text" name="phone">

    <br><br>

    <label>آدرس:</label>
    <input type="text" name="address">

    <br><br>

    <label>توضیحات:</label>
    <textarea name="description"></textarea>

    <br><br>

    <button type="submit">ثبت تأمین‌کننده</button>

</form>

</body>
</html>
