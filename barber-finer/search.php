
<?php
require_once "config/db.php";

$name = trim($_GET["name"] ?? "");
$min_price = $_GET["min_price"] ?? "";
$max_price = $_GET["max_price"] ?? "";

$sql = "SELECT * FROM services WHERE 1=1";
$params = [];

if ($name !== "") {
    $sql .= " AND name LIKE ?";
    $params[] = "%$name%";
}

if ($min_price !== "") {
    $sql .= " AND price >= ?";
    $params[] = $min_price;
}

if ($max_price !== "") {
    $sql .= " AND price <= ?";
    $params[] = $max_price;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>جست‌وجوی خدمات</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h2>جست‌وجوی پیشرفته خدمات</h2>

<form method="GET">

    <label>نام خدمت:</label>
    <input type="text" name="name"
           value="<?= htmlspecialchars($name) ?>">

    <label>حداقل قیمت:</label>
    <input type="number" name="min_price"
           value="<?= htmlspecialchars($min_price) ?>">

    <label>حداکثر قیمت:</label>
    <input type="number" name="max_price"
           value="<?= htmlspecialchars($max_price) ?>">

    <button type="submit">جست‌وجو</button>

</form>

<hr>

<h3>نتایج جست‌وجو</h3>

<?php foreach ($services as $service): ?>
    <div>
        <h4><?= htmlspecialchars($service["name"]) ?></h4>
        <p>
            قیمت:
            <?= number_format($service["price"]) ?> تومان
        </p>
    </div>
    <hr>
<?php endforeach; ?>

</body>
</html>
