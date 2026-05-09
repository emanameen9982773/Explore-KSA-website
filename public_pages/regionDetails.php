<?php
include("../database_connection.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: regionsGallary.php");
    exit();
}

$region_id = (int)$_GET['id'];

$region_query = "SELECT * FROM Regions WHERE region_id = $region_id";
$region_result = mysqli_query($conn, $region_query);
$region_data = mysqli_fetch_assoc($region_result);

if (!$region_data) {
    die("عذراً، المنطقة غير موجودة.");
}

$landmarks_query = "SELECT landmark FROM Landmarks WHERE region_id = $region_id";
$landmarks_res = mysqli_query($conn, $landmarks_query);

$activities_query = "SELECT activity FROM Activities WHERE region_id = $region_id";
$activities_res = mysqli_query($conn, $activities_query);

$images_query = "SELECT image_path FROM Images WHERE region_id = $region_id";
$images_res = mysqli_query($conn, $images_query);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تفاصيل <?php echo $region_data['region_name']; ?></title>
    <link rel="stylesheet" href="../style.css">
  
</head>
<body>

    <header>
        <nav>
            <ul>
                <li> <a href="../index.php">الرئيسية</a></li>
                <li> <a href="regionsGallary.php">معرض المناطق</a></li>
                <li> <a href="../admin_pages/AdminLogin.php">دخول المشرف</a></li>
            </ul>
        </nav>
    </header>

    <main class="details-container">
        <img src="../image/<?php echo $region_data['icon_path']; ?>" class="main-img" alt="صورة المنطقة">
        
        <div class="info-section">
            <h1><?php echo $region_data['region_name']; ?></h1>
            <p style="color: #718096; font-size: 1.2rem;"><?php echo $region_data['headline']; ?></p>
        </div>

        <div class="info-section">
            <h3>عن المنطقة</h3>
            <p><?php echo nl2br($region_data['description']); ?></p>
        </div>

        <div class="info-section">
            <h3>أبرز المعالم السياحية</h3>
            <ul class="grid-list">
                <?php while($row = mysqli_fetch_assoc($landmarks_res)) {
                    echo "<li>" . $row['landmark'] . "</li>";
                } ?>
            </ul>
        </div>

        <div class="info-section">
            <h3>الأنشطة المقترحة</h3>
            <ul class="grid-list">
                <?php while($row = mysqli_fetch_assoc($activities_res)) {
                    echo "<li>" . $row['activity'] . "</li>";
                } ?>
            </ul>
        </div>

        <div class="info-section">
            <h3>معرض الصور</h3>
            <div class="extra-images">
                <?php while($row = mysqli_fetch_assoc($images_res)) { ?>
                    <img src="../image/<?php echo $row['image_path']; ?>" alt="صورة إضافية">
                <?php } ?>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="regionsGallary.php" class="view-btn" style="border-radius: 10px; display: inline-block;">العودة للمعرض</a>
        </div>
    </main>

    <footer>
        <p>استكشف جمال المملكة &copy; 2026</p>
    </footer>

</body>
</html>