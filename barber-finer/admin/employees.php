
<?php
session_start();

require_once "../config/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// بررسی اینکه کاربر واردشده مدیر اصلی است
$stmt = $conn->prepare(
    "SELECT profile_id FROM admins WHERE id = ?"
);
$stmt->execute([$_SESSION["admin_id"]]);
$currentAdmin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$currentAdmin || $currentAdmin["profile_id"] != 1) {
    die("شما اجازه دسترسی به این صفحه را ندارید.");
}

$error = "";
$success = "";

// دریافت نمایه‌ها
$profiles = $conn->query(
    "SELECT * FROM admin_profiles"
)->fetchAll(PDO::FETCH_ASSOC);

// ثبت کارمند جدید
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = trim($_POST["full_name"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $profile_id = (int) $_POST["profile_id"];

    if (
        $full_name === "" ||
        $username === "" ||
        $password === ""
    ) {
        $error = "لطفاً تمام فیلدها را پر کنید.";
    } else {

        $check = $conn->prepare(
            "SELECT id FROM admins WHERE username = ?"
        );
        $check->execute([$username]);

        if ($check->fetch()) {
            $error = "این نام کاربری قبلاً ثبت شده است.";
        } else {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare(
                "INSERT INTO admins
                (username, password, full_name, profile_id)
                VALUES (?, ?, ?, ?)"
            );

            $stmt->execute([
                $username,
                $hashedPassword,
                $full_name,
                $profile_id
            ]);

            $success = "کارمند جدید با موفقیت ثبت شد.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>افزودن کارمند</title>
</head>
<body>

    <h2>افزودن کارمند جدید</h2>

    <?php if ($error): ?>
        <p style="color:red;">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green;">
            <?= htmlspecialchars($success) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>نام و نام خانوادگی:</label>
        <input type="text" name="full_name" required>

        <br><br>

        <label>نام کاربری:</label>
        <input type="text" name="username" required>

        <br><br>

        <label>رمز عبور:</label>
        <input type="password" name="password" required>

        <br><br>

        <label>نمایه کارمند:</label>

        <select name="profile_id" required>
            <?php foreach ($profiles as $profile): ?>
                <option value="<?= $profile["id"] ?>">
                    <?= htmlspecialchars($profile["name"]) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <button type="submit">
            ثبت کارمند
        </button>

    </form>

</body>
</html>
