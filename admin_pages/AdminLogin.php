<?php
session_start();
require_once '../database_connection.php'; 

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT id, username, password FROM admins WHERE username = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                
                // للتحقق من كلمة المرور 
                if ($password === $row['password']) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["admin_id"] = $row['id'];
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
    } else {
        $error = "الرجاء إدخال اسم المستخدم وكلمة المرور.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول - الإدارة</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1 style="text-align: center;">تسجيل دخول الإدارة</h1>
    </header>

    <main>
        <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
            
            <?php 
            if(!empty($error)){
                echo '<div style="color: red; text-align: center; margin-bottom: 15px; font-weight: bold;">' . $error . '</div>';
            }        
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px;">اسم المستخدم:</label>
                    <input type="text" name="username" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                </div>    
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px;">كلمة المرور:</label>
                    <input type="password" name="password" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                </div>
                <div>
                    <button type="submit" style="width: 100%; padding: 10px; background-color: #2c3e50; color: white; border: none; border-radius: 4px; cursor: pointer;">دخول</button>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <p style="text-align: center; margin-top: 50px;">&copy; اكتشف السعودية</p>
    </footer>
</body>
</html>