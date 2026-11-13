
<?php
require_once "../config/db.php";

$stmt = $conn->query("SELECT * FROM payment_settings ORDER BY id DESC");
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $method = trim($_POST["method_name"]);
    $holder = trim($_POST["account_holder"]);
    $card = trim($_POST["card_number"]);
    $iban = trim($_POST["iban"]);
    $description = trim($_POST["description"]);

    $stmt = $conn->prepare(
        "INSERT INTO payment_settings
        (method_name, account_holder, card_number, iban, description)
        VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->execute([
        $method,
        $holder,
        $card,
        $iban,
        $description
    ]);

    header("Location: payment_settings.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تنظیمات پرداخت</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>تنظیمات روش پرداخت</h2>

<form method="POST">
    <label>نام روش پرداخت:</label>
    <input type="text" name="method_name" required>

    <label>نام صاحب حساب:</label>
    <input type="text" name="account_holder">

    <label>شماره کارت:</label>
    <input type="text" name="card_number">

    <label>شماره شبا:</label>
    <input type="text" name="iban">

    <label>توضیحات:</label>
    <textarea name="description"></textarea>

    <button type="submit">ثبت روش پرداخت</button>
</form>

<hr>

<h3>روش‌های پرداخت ثبت‌شده</h3>

<?php foreach ($payments as $payment): ?>
    <div>
        <h4><?= htmlspecialchars($payment["method_name"]) ?></h4>
        <p>صاحب حساب: <?= htmlspecialchars($payment["account_holder"]) ?></p>
        <p>شماره کارت: <?= htmlspecialchars($payment["card_number"]) ?></p>
        <p>شماره شبا: <?= htmlspecialchars($payment["iban"]) ?></p>
        <p><?= htmlspecialchars($payment["description"]) ?></p>
    </div>
    <hr>
<?php endforeach; ?>

</body>
</html>
