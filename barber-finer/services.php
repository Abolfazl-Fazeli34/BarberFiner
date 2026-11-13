<?php
require_once "config/db.php";
$stmt = $conn->prepare(
    "SELECT * FROM services
     WHERE is_active = 1
     ORDER BY id DESC"
);
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خدمات آرایشگاه</title>
    <style>
        body {
            font-family: Tahoma;
            background: #f4f4f4;
            margin: 0;
            padding: 30px;
        }

        h1 {
            text-align: center;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .service {
            background: white;
            width: 250px;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px #ddd;
        }
        .service h2 {
            color: #222;
        }
        .price {
            color: #16834a;
            font-weight: bold;
        }
    </style>
</head>
<body>
<h1>خدمات آرایشگاه مردانه</h1>
<div class="container">
    <?php foreach ($services as $service): ?>
        <div class="service">
            <h2>
                <?= htmlspecialchars($service['name']) ?>
            </h2>
            <p>
                <?= htmlspecialchars($service['description']) ?>
            </p>
            <p class="price">
                قیمت:
                <?= number_format($service['price']) ?>
                تومان
            </p>
            <p>
                مدت زمان:
                <?= htmlspecialchars($service['duration_minutes']) ?>
                دقیقه
            </p>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
