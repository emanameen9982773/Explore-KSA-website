<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل منطقة</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <header>
        <nav>
            <ul>
                <li><a href="dashboard.php">لوحة تحكم المشرف</a></li>
                <li><a href="../public_pages/regionsGallary.php">معرض المناطق</a></li>
                <li><a href="AdminLogin.php">تسجيل خروج</a></li>
            </ul>
        </nav>
    </header>

    <main class="admin-form-container">
        <?php
        include("../database_connection.php");

        if (!isset($_GET['region_id']) || !is_numeric($_GET['region_id'])) {
            echo '<div class="alert-error">معرّف المنطقة غير صالح.</div>';
            exit();
        }

        $region_id = (int) $_GET['region_id'];

        $query = "SELECT * FROM Regions WHERE region_id = $region_id";
        $result = mysqli_query($conn, $query);

        if (!$result || mysqli_num_rows($result) === 0) {
            echo '<div class="alert-error">المنطقة غير موجودة.</div>';
            exit();
        }

        $region = mysqli_fetch_assoc($result);

        $landmarks_result = mysqli_query($conn, "SELECT landmark FROM Landmarks WHERE region_id = $region_id");
        $landmarks = [];
        while ($row = mysqli_fetch_assoc($landmarks_result)) {
            $landmarks[] = htmlspecialchars($row['landmark']);
        }

        $activities_result = mysqli_query($conn, "SELECT activity FROM Activities WHERE region_id = $region_id");
        $activities = [];
        while ($row = mysqli_fetch_assoc($activities_result)) {
            $activities[] = htmlspecialchars($row['activity']);
        }

        $images_result = mysqli_query($conn, "SELECT image_id, image_path FROM Images WHERE region_id = $region_id");
        $images = [];
        while ($row = mysqli_fetch_assoc($images_result)) {
            $images[] = $row;
        }

        if (isset($_GET['error']) && $_GET['error'] == 'duplicate') {
            echo '<div id="alert-msg" class="alert-error">عذراً، هذا الاسم مستخدم لمنطقة أخرى!</div>';
        } elseif (isset($_GET['success']) && $_GET['success'] == 0) {
            echo '<div id="alert-msg" class="alert-error">عذراً، فشلت عملية التعديل!</div>';
        }
        ?>

        <h1>تعديل منطقة</h1>
        <p>عدّل معلومات المنطقة:</p>

        <form action="editRegionLogic.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="region_id" value="<?= $region_id ?>">

            <div class="admin-form-group">
                <label for="region_name">اسم المنطقة</label>
                <input type="text" name="region_name" id="region_name"
                       value="<?= htmlspecialchars($region['region_name']) ?>" required>
            </div>

            <div class="admin-form-group">
                <label for="headline">وصف مختصر</label>
                <input type="text" name="headline" id="headline"
                       placeholder="يظهر أسفل اسم المنطقة في صفحة المعرض"
                       value="<?= htmlspecialchars($region['headline']) ?>">
            </div>

            <div class="admin-form-group">
                <label for="nature">طبيعة المنطقة</label>
                <select name="nature" id="nature" required>
                    <option value="" disabled>-- اختر نوع الطبيعة --</option>
                    <?php
                    $natures = ['صحراوية', 'جبلية', 'ساحلية', 'زراعية', 'بركانية', 'جزيرة'];
                    $nature_labels = ['صحراوية' => 'صحراوية', 'جبلية' => 'جبلية', 'ساحلية' => 'ساحلية',
                                      'زراعية' => 'زراعية/ريفية', 'بركانية' => 'بركانية/حرات', 'جزيرة' => 'جزيرة'];
                    foreach ($natures as $n):
                        $selected = ($region['nature'] === $n) ? 'selected' : '';
                    ?>
                        <option value="<?= $n ?>" <?= $selected ?>><?= $nature_labels[$n] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="admin-form-group">
                <label for="location">موقعها بالنسبة للمملكة</label>
                <select name="location" id="location" required>
                    <option value="" disabled>-- اختر موقعاً --</option>
                    <?php
                    $locations = ['شمال' => 'شمال المملكة', 'جنوب' => 'جنوب المملكة',
                                  'شرق'  => 'شرق المملكة',  'غرب'  => 'غرب المملكة', 'وسط' => 'وسط المملكة'];
                    foreach ($locations as $val => $label):
                        $selected = ($region['location'] === $val) ? 'selected' : '';
                    ?>
                        <option value="<?= $val ?>" <?= $selected ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="admin-form-group">
                <label>أبرز المعالم السياحية</label>
                <?php if (!empty($landmarks)): ?>
                    <?php foreach ($landmarks as $i => $lm): ?>
                        <div class="dynamic-input-row">
                            <input type="text" name="landmarks[]" value="<?= $lm ?>">
                            <?php if ($i === 0): ?>
                                <button type="button" class="add-row-btn" onclick="addInput(this)">+</button>
                            <?php else: ?>
                                <button type="button" class="remove-row-btn" onclick="removeInput(this)">−</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="dynamic-input-row">
                        <input type="text" name="landmarks[]">
                        <button type="button" class="add-row-btn" onclick="addInput(this)">+</button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="admin-form-group">
                <label>أبرز الأنشطة</label>
                <?php if (!empty($activities)): ?>
                    <?php foreach ($activities as $i => $act): ?>
                        <div class="dynamic-input-row">
                            <input type="text" name="activities[]" value="<?= $act ?>">
                            <?php if ($i === 0): ?>
                                <button type="button" class="add-row-btn" onclick="addInput(this)">+</button>
                            <?php else: ?>
                                <button type="button" class="remove-row-btn" onclick="removeInput(this)">−</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="dynamic-input-row">
                        <input type="text" name="activities[]" placeholder="مثال: الغوص، رياضة الهايكنج">
                        <button type="button" class="add-row-btn" onclick="addInput(this)">+</button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="admin-form-group">
                <label for="description">وصف المنطقة المفصل</label>
                <textarea name="description" id="description" rows="4"
                          placeholder="معلومات عامة عن المنطقة تظهر في صفحة التفاصيل"
                ><?= htmlspecialchars($region['description']) ?></textarea>
            </div>

            <div class="admin-form-group">
                <label>الصورة الرئيسية الحالية</label>
                <?php if (!empty($region['icon_path'])): ?>
                    <div class="current-image-preview">
                        <img src="../image/<?= htmlspecialchars($region['icon_path']) ?>"
                             alt="الصورة الرئيسية" style="max-height:120px; border-radius:8px;">
                        <p style="font-size:.85rem; color:#666;">
                            اترك حقل الرفع فارغاً للإبقاء على الصورة الحالية
                        </p>
                    </div>
                <?php endif; ?>
                <label for="icon">استبدال الصورة الرئيسية (اختياري)</label>
                <input type="file" id="icon" name="icon" accept="image/*">
            </div>

            <?php if (!empty($images)): ?>
            <div class="admin-form-group">
                <label>الصور الإضافية الحالية</label>
                <div class="existing-images-grid">
                    <?php foreach ($images as $img): ?>
                        <div class="existing-image-item">
                            <img src="../image/<?= htmlspecialchars($img['image_path']) ?>"
                                 alt="صورة إضافية" style="max-height:80px; border-radius:6px;">
                            <label style="font-size:.8rem; color:#c0392b;">
                                <input type="checkbox" name="delete_images[]"
                                       value="<?= $img['image_id'] ?>">
                                حذف
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="admin-form-group">
                <label>إضافة صور جديدة للمنطقة</label>
                <div id="images-container" class="dynamic-input-row">
                    <input type="file" name="images[]" accept="image/*">
                    <button type="button" class="add-row-btn" onclick="addInput(this)">+</button>
                </div>
            </div>

            <button id="addRegionSubmit" type="submit">حفظ التعديلات</button>

        </form>
    </main>

    <footer>
        <p>استكشف جمال المملكة &copy; 2026</p>
    </footer>
    <script src="script.js"></script>
    <script>
        function removeInput(btn) {
            btn.parentElement.remove();
        }
    </script>

</body>
</html>