
<?php
session_start();
require_once "../config/db.php";

// بررسی ورود مدیر
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";
$error = "";

// ثبت کد تخفیف
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $code = strtoupper(trim($_POST["code"] ?? ""));
    $discount_percent = (int)($_POST["discount_percent"] ?? 0);
    $start_date = $_POST["start_date"] ?? "";
    $end_date = $_POST["end_date"] ?? "";
    $usage_limit = (int)($_POST["usage_limit"] ?? 0);

    if (
        $code === "" ||
        $discount_percent < 1 ||
        $discount_percent > 100 ||
        $start_date === "" ||
        $end_date === "" ||
        $usage_limit < 1
    ) {
        $error = "لطفاً تمام فیلدها را درست وارد کنید.";
    } elseif ($end_date < $start_date) {
        $error = "تاریخ پایان نباید قبل از تاریخ شروع باشد.";
    } else {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO discounts
                (code, discount_percent, start_date, end_date, usage_limit)
                VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $code,
                $discount_percent,
                $start_date,
                $end_date,
                $usage_limit
            ]);

            header("Location: discounts.php?success=1");
            exit;

        } catch (PDOException $e) {
            $error = "این کد تخفیف قبلاً ثبت شده یا خطایی در ذخیره اطلاعات رخ داده است.";
        }
    }
}

// پیام موفقیت
if (isset($_GET["success"])) {
    $message = "کد تخفیف با موفقیت ثبت شد.";
}

// دریافت کدهای تخفیف
$stmt = $conn->query("SELECT * FROM discounts ORDER BY id DESC");
$discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت کدهای تخفیف</title>

    <link rel="stylesheet" href="../assets/css/discounts.css">
</head>

<body>

<div class="container">

    <h1>مدیریت کدهای تخفیف</h1>
    <p class="subtitle">ایجاد و مدیریت تخفیف‌های Online Barber Shop</p>

    <?php if ($message !== ""): ?>
        <div class="success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if ($error !== ""): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="form-box">

        <h2>ایجاد کد تخفیف جدید</h2>

        <form method="POST">

            <div class="form-group">
                <label>کد تخفیف</label>
                <input
                    type="text"
                    name="code"
                    placeholder="مثلاً BARBER20"
                    required
                >
            </div>

            <div class="form-group">
                <label>درصد تخفیف</label>
                <input
                    type="number"
                    name="discount_percent"
                    min="1"
                    max="100"
                    placeholder="مثلاً 20"
                    required
                >
            </div>

            <div class="form-group">
                <label>تاریخ شروع</label>
                <input type="date" name="start_date" required>
            </div>

            <div class="form-group">
                <label>تاریخ پایان</label>
                <input type="date" name="end_date" required>
            </div>

            <div class="form-group">
                <label>تعداد دفعات مجاز استفاده</label>
                <input
                    type="number"
                    name="usage_limit"
                    min="1"
                    placeholder="مثلاً 50"
                    required
                >
            </div>

            <button type="submit">ثبت کد تخفیف</button>

        </form>
    </div>

    <div class="table-box">

        <h2>کدهای تخفیف ثبت‌شده</h2>

        <table>
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>کد تخفیف</th>
                    <th>درصد تخفیف</th>
                    <th>تاریخ شروع</th>
                    <th>تاریخ پایان</th>
                    <th>تعداد مجاز</th>
                    <th>تعداد استفاده‌شده</th>
                </tr>
            </thead>

            <tbody>
                <?php if (count($discounts) > 0): ?>

                    <?php foreach ($discounts as $discount): ?>
                        <tr>
                            <td><?= (int)$discount["id"] ?></td>

                            <td>
                                <?= htmlspecialchars($discount["code"]) ?>
                            </td>

                            <td>
                                <?= (int)$discount["discount_percent"] ?>٪
                            </td>

                            <td>
                                <?= htmlspecialchars($discount["start_date"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($discount["end_date"]) ?>
                            </td>

                            <td>
                                <?= (int)$discount["usage_limit"] ?>
                            </td>

                            <td>
                                <?= (int)$discount["used_count"] ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7">هنوز کد تخفیفی ثبت نشده است.</td>
                    </tr>

                <?php endif; ?>
            </tbody>
        </table>

    </div>

</div>

</body>
</html>
