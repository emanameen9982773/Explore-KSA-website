<?php
session_start();

// Check if the user is logged in, if not then redirect back to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: AdminLogin.php");
    exit;
}

include("../database_connection.php");

// ── Handle DELETE ──────────────────────────────────────────────
$deleteMessage = '';
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $del_id = (int) $_GET['delete_id'];
    try {
        mysqli_query($conn, "DELETE FROM Landmarks WHERE region_id = $del_id");
        mysqli_query($conn, "DELETE FROM Activities WHERE region_id = $del_id");
        mysqli_query($conn, "DELETE FROM Images    WHERE region_id = $del_id");
        $result = mysqli_query($conn, "DELETE FROM Regions WHERE region_id = $del_id");
        if ($result && mysqli_affected_rows($conn) > 0) {
            $deleteMessage = 'success';
        } else {
            $deleteMessage = 'fail';
        }
    } catch (Exception $e) {
        $deleteMessage = 'fail';
    }
}

// ── Fetch all regions ──────────────────────────────────────────
$regions = [];
$regionsResult = mysqli_query($conn, "SELECT region_id, region_name, nature, location, headline FROM Regions ORDER BY region_id ASC");
if ($regionsResult) {
    while ($row = mysqli_fetch_assoc($regionsResult)) {
        $regions[] = $row;
    }
}
$regionCount = count($regions);

include("../database_connection.php");

// ── Handle DELETE ──────────────────────────────────────────────
$deleteMessage = '';
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $del_id = (int) $_GET['delete_id'];
    try {
        mysqli_query($conn, "DELETE FROM Landmarks WHERE region_id = $del_id");
        mysqli_query($conn, "DELETE FROM Activities WHERE region_id = $del_id");
        mysqli_query($conn, "DELETE FROM Images    WHERE region_id = $del_id");
        $result = mysqli_query($conn, "DELETE FROM Regions WHERE region_id = $del_id");
        if ($result && mysqli_affected_rows($conn) > 0) {
            $deleteMessage = 'success';
        } else {
            $deleteMessage = 'fail';
        }
    } catch (Exception $e) {
        $deleteMessage = 'fail';
    }
}

// ── Fetch all regions ──────────────────────────────────────────
$regions = [];
$regionsResult = mysqli_query($conn, "SELECT region_id, region_name, nature, location, headline FROM Regions ORDER BY region_id ASC");
if ($regionsResult) {
    while ($row = mysqli_fetch_assoc($regionsResult)) {
        $regions[] = $row;
    }
}
$regionCount = count($regions);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المشرف</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../Dashboard.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../Dashboard.css">
</head>

<body>

    <!-- ===== Header (same as other admin pages) ===== -->
    <!-- ===== Header (same as other admin pages) ===== -->
    <header>
        <nav>
            <ul>
                <li><a href="dashboard.php">لوحة تحكم المشرف</a></li>
                <li><a href="addContent.php">إضافة منطقة</a></li>
                <li><a href="../public_pages/regionsGallary.php">معرض المناطق</a></li>
                <li><a href="AdminLogin.php">تسجيل خروج</a></li>
            </ul>
        </nav>
        <nav>
            <ul>
                <li><a href="dashboard.php">لوحة تحكم المشرف</a></li>
                <li><a href="addContent.php">إضافة منطقة</a></li>
                <li><a href="../public_pages/regionsGallary.php">معرض المناطق</a></li>
                <li><a href="AdminLogin.php">تسجيل خروج</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-main">

        <!-- ── Alert Messages ── -->
        <?php if ($deleteMessage === 'success'): ?>
            <div class="alert-success">✅ &nbsp; تم حذف السجل بنجاح!</div>
        <?php elseif ($deleteMessage === 'fail'): ?>
            <div class="alert-error">❌ &nbsp; فشلت عملية الحذف، حاول مرة أخرى.</div>
        <?php endif; ?>

        <?php
          // after adding a new region successfully the dashboard page should be opened with a message
          if (isset($_GET['success']) && $_GET['success'] == 1) {
              echo '<div class="alert-success">✅ &nbsp; تمت الإضافة بنجاح!</div>';
          }
        ?>

        <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
            <div class="alert-success">✅ &nbsp; تم تحديث المنطقة بنجاح!</div>
        <?php endif; ?>

        <!-- ── Dashboard Hero ── -->
        <div class="dashboard-hero">
            <div>
                <h1>إدارة المحتوى</h1>
                <p>استخدم هذه الصفحة لإدارة محتوى الموقع من خلال عرض السجلات وإضافتها أو تعديلها أو حذفها</p>
            </div>
            <a href="addContent.php" class="add-region-btn">＋ &nbsp; إضافة منطقة جديدة</a>
        </div>

        <!-- ── Regions Table ── -->
        <div class="table-card">
            <div class="table-card-header">
                <h2>جميع المناطق</h2>
                <span class="region-count-badge">عدد النتائج: <?= $regionCount ?></span>
            </div>

            <?php if ($regionCount === 0): ?>
                <div class="empty-state">
                    <div class="empty-icon">🏜️</div>
                    <p>لا توجد مناطق مضافة بعد. ابدأ بإضافة منطقة جديدة!</p>
                </div>
            <?php else: ?>
                <table class="regions-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>المنطقة</th>
                            <th>التصنيف</th>
                            <th>الموقع</th>
                            <th>الوصف</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($regions as $region): ?>
                            <tr>
                                <td><?= htmlspecialchars($region['region_id']) ?></td>
                                <td class="region-name-cell"><?= htmlspecialchars($region['region_name']) ?></td>
                                <td><span class="nature-badge"><?= htmlspecialchars($region['nature']) ?></span></td>
                                <td><span class="location-badge"><?= htmlspecialchars($region['location']) ?></span></td>
                                <td class="desc-cell"><?= htmlspecialchars($region['headline'] ?? '') ?></td>
                                <td>
                                    <div class="actions-cell">
                                        <a href="editContent.php?id=<?= $region['region_id'] ?>" class="btn-edit">
                                            ✏️ تعديل
                                        </a>
                                        <button
                                            class="btn-delete"
                                            onclick="confirmDelete(<?= $region['region_id'] ?>, '<?= addslashes(htmlspecialchars($region['region_name'])) ?>')">
                                            🗑️ حذف
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </main>

    <!-- ===== Delete Confirmation Modal ===== -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div class="modal-icon">⚠️</div>
            <h3>تأكيد الحذف</h3>
            <p>هل تريد حذف هذا السجل؟<br><strong id="regionNameInModal"></strong></p>
            <div class="modal-actions">
                <a href="#" id="confirmDeleteBtn" class="btn-confirm-delete">نعم، احذف</a>
                <button class="btn-cancel" onclick="closeModal()">إلغاء</button>
            </div>
        </div>
    </div>

    <!-- ===== Delete Confirmation Modal ===== -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div class="modal-icon">⚠️</div>
            <h3>تأكيد الحذف</h3>
            <p>هل تريد حذف هذا السجل؟<br><strong id="regionNameInModal"></strong></p>
            <div class="modal-actions">
                <a href="#" id="confirmDeleteBtn" class="btn-confirm-delete">نعم، احذف</a>
                <button class="btn-cancel" onclick="closeModal()">إلغاء</button>
            </div>
        </div>
    </div>

    <footer>
        <p>استكشف جمال المملكة &copy; 2026</p>
    <footer>
        <p>استكشف جمال المملكة &copy; 2026</p>
    </footer>

    <script>
        function confirmDelete(regionId, regionName) {
            document.getElementById('regionNameInModal').textContent = regionName;
            document.getElementById('confirmDeleteBtn').href = 'dashboard.php?delete_id=' + regionId;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }

        // Close modal when clicking outside the box
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert-success, .alert-error').forEach(function(el) {
                el.style.transition = 'opacity 0.6s';
                el.style.opacity = '0';
                setTimeout(function() { el.remove(); }, 600);
            });
        }, 5000);
    </script>
	<script src="script.js"></script>
</body>
</html>