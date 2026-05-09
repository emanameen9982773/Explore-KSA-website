<?php
session_start();
require_once '../database_connection.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        
        // 1. FIXED: Removed 'id' and changed table name to 'Admins'
        $sql = "SELECT username, password FROM Admins WHERE username = ?";

        // 2. FIXED: Added try...catch block to handle errors gracefully
        try {
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();

                    // للتحقق من كلمة المرور
                    if ($password === $row['password']) {
                        $_SESSION["loggedin"]       = true;
                        
                        // 3. FIXED: Removed $_SESSION["admin_id"] because the table has no ID column
                        $_SESSION["admin_username"] = $row['username'];

                        header("location: dashboard.php");
                        exit;
                    } else {
                        $error = "كلمة المرور غير صحيحة.";
                    }
                } else {
                    $error = "اسم المستخدم غير موجود.";
                }
                $stmt->close();
            }
        } catch (mysqli_sql_exception $e) {
            // This stops the white screen crash and shows the error in the red box
            $error = "حدث خطأ في قاعدة البيانات: " . $e->getMessage(); 
        }
        
    } else {
        $error = "الرجاء إدخال اسم المستخدم وكلمة المرور.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - لوحة المشرف</title>
    <link rel="stylesheet" href="admin-login.css">
</head>
<body>

    <!-- ===== Navbar ===== -->
    <nav class="navbar">
        <span class="nav-brand">لوحة المشرف</span>
        <div class="nav-links">
            <a href="../index.php">الصفحة الرئيسية</a>
            <a href="../public_pages/regionsGallary.php">معرض المناطق</a>
        </div>
    </nav>

    <!-- ===== Login Card ===== -->
    <main class="login-wrapper">
        <div class="login-card">

            <div class="card-header">
                <h1>تسجيل دخول المشرف</h1>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-box" id="errorBox">
                    &#9888; <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="loginForm">

                <div class="form-group">
                    <label for="username">اسم المستخدم</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="مثال: admin"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    >
                    <span class="field-error" id="usernameErr"></span>
                </div>

                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                    >
                    <span class="field-error" id="passwordErr"></span>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">دخول</button>

            </form>
        </div>
    </main>

    <!-- ===== Footer ===== -->
    <footer class="footer">
        <p>&#169; اكتشف السعودية &mdash; جامعة الملك سعود</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>