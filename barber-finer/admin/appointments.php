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

// تغییر وضعیت نوبت
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $appointment_id = (int)($_POST["appointment_id"] ?? 0);
    $status = $_POST["status"] ?? "";

    $allowed_statuses = [
        "pending",
        "confirmed",
        "cancelled"
    ];

    if (
        $appointment_id > 0 &&
        in_array($status, $allowed_statuses, true)
    ) {
        $update = $conn->prepare(
            "UPDATE appointments SET status = ? WHERE id = ?"
        );

        $update->execute([
            $status,
            $appointment_id
        ]);

        if ($update->rowCount() > 0) {
            $message = "وضعیت نوبت با موفقیت تغییر کرد.";
        } else {
            $message = "درخواستی برای تغییر وضعیت ثبت شد.";
        }

    } else {
        $error = "اطلاعات واردشده معتبر نیست.";
    }
}

// دریافت فهرست نوبت‌ها
$stmt = $conn->query("
    SELECT
        appointments.*,
        services.name AS service_name,
        barbers.name AS barber_name
    FROM appointments
    LEFT JOIN services
        ON appointments.service_id = services.id
    LEFT JOIN barbers
        ON appointments.barber_id = barbers.id
    ORDER BY appointments.id DESC
");

$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ترجمه وضعیت‌ها
$status_labels = [
    "pending" => "در انتظار",
    "confirmed" => "تأیید شده",
    "cancelled" => "لغو شده"
];
?>

<style>
    body {
        font-family: Tahoma, Arial, sans-serif;
        background: #f4f6f9;
        direction: rtl;
        margin: 0;
        padding: 25px;
    }

    h2 {
        color: #222;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
    }

    th, td {
        padding: 13px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th {
        background: #172b4d;
        color: white;
    }

    tr:hover {
        background: #f1f5f9;
    }

    select, button {
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    button {
        background: #1769aa;
        color: white;
        cursor: pointer;
        border: none;
    }

    button:hover {
        background: #0d4f85;
    }

    a {
        color: #1769aa;
        text-decoration: none;
    }
</style>

<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>مدیریت نوبت‌ها</title>
</head>
<body>
<div class="container">
    <h1>مدیریت نوبت‌های مشتریان</h1>
    <p class="subtitle">
        مشاهده و مدیریت نوبت‌های Online Barber Shop
    </p>

    <?php if ($message !== ""): ?>
        <div class="success">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if ($error !== ""): ?>
        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="table-box">

        <h2>فهرست نوبت‌های ثبت‌شده</h2>

        <table>

            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>نام مشتری</th>
                    <th>شماره تماس</th>
                    <th>خدمت</th>
                    <th>آرایشگر</th>
                    <th>تاریخ</th>
                    <th>ساعت</th>
                    <th>وضعیت</th>
                    <th>تغییر وضعیت</th>
                    <th>جزئیات</th>
                </tr>
            </thead>

            <tbody>

                <?php if (count($appointments) > 0): ?>

                    <?php foreach ($appointments as $appointment): ?>

                        <tr>

                            <td>
                                <?= (int)$appointment["id"] ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($appointment["customer_name"] ?? "") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($appointment["customer_phone"] ?? "") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($appointment["service_name"] ?? "نامشخص") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($appointment["barber_name"] ?? "نامشخص") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($appointment["appointment_date"] ?? "") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($appointment["appointment_time"] ?? "") ?>
                            </td>

                            <td>
                                <span class="status">
                                    <?= htmlspecialchars(
                                        $status_labels[$appointment["status"]] 
                                        ?? $appointment["status"]
                                    ) ?>
                                </span>
                            </td>

                            <td>

                                <form method="POST" class="status-form">

                                    <input
                                        type="hidden"
                                        name="appointment_id"
                                        value="<?= (int)$appointment["id"] ?>"
                                    >

                                    <select name="status">

                                        <option value="pending"
                                            <?= $appointment["status"] === "pending" ? "selected" : "" ?>>
                                            در انتظار
                                        </option>

                                        <option value="confirmed"
                                            <?= $appointment["status"] === "confirmed" ? "selected" : "" ?>>
                                            تأیید شده
                                        </option>

                                        <option value="cancelled"
                                            <?= $appointment["status"] === "cancelled" ? "selected" : "" ?>>
                                            لغو شده
                                        </option>

                                    </select>

                                    <button type="submit">
                                        ذخیره
                                    </button>

                                </form>

                            </td>

                            <td>
                                <a
                                    class="view-btn"
                                    href="appointment-details.php?id=<?= (int)$appointment["id"] ?>"
                                >
                                    مشاهده
                                </a>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="10">
                            هنوز هیچ نوبتی ثبت نشده است.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>
