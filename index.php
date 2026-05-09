<?php
include("database_connection.php");

// Get region count for stats
$countResult = mysqli_query($conn, "SELECT COUNT(*) as total FROM Regions");
$regionCount = $countResult ? mysqli_fetch_assoc($countResult)['total'] : 0;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اكتشف السعودية</title>
    <link rel="stylesheet" href="../index.css">
</head>

<body>

    <!-- ===== Header ===== -->
    <header>
        <nav>
            <ul>
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="public_pages/regionsGallary.php">معرض المناطق</a></li>
                <li><a href="admin_pages/AdminLogin.php">دخول المشرف</a></li>
            </ul>
        </nav>
    </header>

    <!-- ===== Hero ===== -->
    <section class="hero">
        <div class="hero-content">
            <span class="hero-badge">🌴 &nbsp; اكتشف المملكة العربية السعودية</span>
            <h1>موقع ثقافي تفاعلي للتعريف بالمملكة</h1>
            <p>استكشف مناطق المملكة العربية السعودية وتعرّف على أهم المعالم التاريخية والثقافية. اختر منطقة من المعرض للانتقال إلى صفحة التفاصيل.</p>
            <div class="hero-actions">
                <a href="public_pages/regionsGallary.php" class="btn-primary">ابدأ الاستكشاف</a>
                <a href="admin_pages/AdminLogin.php" class="btn-outline">دخول المشرف</a>
            </div>
        </div>
    </section>

    <!-- ===== Stats Strip ===== -->
    <div class="stats-strip">
        <div class="stat-item">
            <div class="stat-number"><?= $regionCount ?>+</div>
            <div class="stat-label">منطقة مسجّلة</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">13</div>
            <div class="stat-label">منطقة إدارية</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">2030</div>
            <div class="stat-label">رؤية المملكة</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">∞</div>
            <div class="stat-label">تجارب لا تُنسى</div>
        </div>
    </div>

    <!-- ===== Main ===== -->
    <main>

        <h2 class="section-title">الهدف</h2>
        <p class="section-subtitle">ما الذي يقدمه هذا الموقع؟</p>

        <div class="features-grid">

            <div class="feature-card">
                <span class="feature-icon">🗺️</span>
                <h3>المناطق</h3>
                <p>يتيح للمستخدم التنقل بين مناطق المملكة إلى الصورة والتفاصيل والمناطق المميزة. اختر منطقة من المعرض لاستكشاف مزيد من المعلومات.</p>
            </div>

            <div class="feature-card">
                <span class="feature-icon">🏛️</span>
                <h3>التفاصيل</h3>
                <p>صفحة تعرض وصفاً وافياً وصوراً ومعلومات تاريخية وأبرز المعالم والأنشطة لكل منطقة من مناطق المملكة.</p>
            </div>

            <div class="feature-card">
                <span class="feature-icon">⚙️</span>
                <h3>لوحة التحكم</h3>
                <p>تقديم معلومات مرتّبة عن مناطق المملكة وأبرز الوجهات، مع إمكانية الإضافة والتعديل والحذف للمشرف.</p>
            </div>

        </div>

    </main>

    <!-- Night Mode Button -->
    <button class="night-mode-btn" onclick="toggleNightMode()" title="الوضع الليلي">🌙</button>

    <!-- ===== Footer ===== -->
    <footer>
        <p>&#169; اكتشف السعودية &mdash; جامعة الملك سعود &nbsp; 2026</p>
    </footer>

    <script>
        function toggleNightMode() {
            document.body.classList.toggle('night-mode');
            const btn = document.querySelector('.night-mode-btn');
            btn.textContent = document.body.classList.contains('night-mode') ? '☀️' : '🌙';
            localStorage.setItem('nightMode', document.body.classList.contains('night-mode'));
        }

        // Restore preference on load
        if (localStorage.getItem('nightMode') === 'true') {
            document.body.classList.add('night-mode');
            document.querySelector('.night-mode-btn').textContent = '☀️';
        }
    </script>

</body>
</html>