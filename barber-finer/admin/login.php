
<?php
session_start();
require_once "../config/db.php";
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $stmt = $conn->prepare(
        "SELECT * FROM admins WHERE username = ?"
    );
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($admin && password_verify($password, $admin["password"])) {
        session_regenerate_id(true);
        $_SESSION["admin_id"] = $admin["id"];
        $_SESSION["admin_name"] = $admin["full_name"];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "نام کاربری یا رمز عبور اشتباه است.";
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ورود مدیر</title>
</head>
<body>
    <h2>ورود به پنل مدیریت آرایشگاه</h2>
    <?php if ($error): ?>
        <p style="color:red;">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>
    <form method="POST">
        <label>نام کاربری:</label>
        <input type="text" name="username" required> 
        <br><br>
        <label>رمز عبور:</label>
        <input type="password" name="password" required>
        <br><br>
        <button type="submit">ورود</button>
    </form>
</body>
</html>
