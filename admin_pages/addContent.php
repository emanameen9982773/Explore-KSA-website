<!DOCTYPE html>
<html lang="ar" dir="rtl"> <head>
    <meta charset="UTF-8">
    <title>إضافة منطقة جديدة</title>
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
        if (isset($_GET['error']) && $_GET['error'] == 'duplicate') {
           echo '<div  class="alert">عذراً، هذه المنطقة موجود مسبقاً!</div>';
        }
        elseif(isset($_GET['success']) && $_GET['success'] == 0)
            echo '<div  id="alert-error" class="alert">عذراً، فشلت عملية الاضافة !</div>';            
        ?>
        <h1>إضافة منطقة جديدة</h1>
        <p>ادخل معلومات المنطقة:</p>

        <form action="addRegionLogic.php" method="POST" enctype="multipart/form-data">
            
            <div class="admin-form-group">
                <label for="region_name">اسم المنطقة</label>
                <input type="text" name="region_name" id="region_name" required>
            </div>

            <div class="admin-form-group">
                <label for="headline">وصف مختصر</label>
                <input type="text" name="headline" id="headline" placeholder="يظهر أسفل اسم المنطقة في صفحة المعرض">
            </div>
            
            <div class="admin-form-group">
                <label for="nature">طبيعة المنطقة</label>
                <select name="nature" id="nature" required>
                    <option value="" disabled selected>-- اختر نوع الطبيعة --</option>
                    <option value="صحراوية">صحراوية</option>
                    <option value="جبلية">جبلية</option>
                    <option value="ساحلية">ساحلية</option>
                    <option value="زراعية">زراعية/ريفية</option>
                    <option value="بركانية">بركانية/حرات</option>
                    <option value="جزيرة">جزيرة</option>
                </select>
            </div>

            <div class="admin-form-group">
                <label for="location">موقعها بالنسبة للمملكة</label>
                <select name="location" id="location" required>
                    <option value="" disabled selected>--اختر موقعاً--</option>
                    <option value="شمال">شمال المملكة</option>
                    <option value="جنوب">جنوب المملكة</option>
                    <option value="شرق">شرق المملكة</option>
                    <option value="غرب">غرب المملكة</option>
                    <option value="وسط">وسط المملكة</option>
                </select>
            </div>

            <div class="admin-form-group">
                <label for="landmarks">أبرز المعالم السياحية</label>
                <div class="dynamic-input-row"> 
                    <input type="text" name="landmarks[]">
                    <button type="button" class="add-row-btn" onclick="addInput(this)">+</button>
                </div>
            </div>

            <div class="admin-form-group">
                <label for="activities">أبرز الأنشطة</label>
                <div class="dynamic-input-row"> 
                    <input type="text" name="activities[]" placeholder="مثال: الغوص، رياضة الهايكنج">
                    <button type="button" class="add-row-btn" onclick="addInput(this)">+</button>
                </div>
            </div>

            <div class="admin-form-group">
                <label for="description">وصف المنطقة المفصل</label>
                <textarea name="description" id="description" rows="4" placeholder="معلومات عامة عن المنطقة تظهر في صفحة التفاصيل"></textarea>
            </div>

            <div class="admin-form-group">
                <label for="icon">الصورة الرئيسية للمنطقة</label>
                <input type="file" id="icon" name="icon" accept="image/*" >
            </div>

            <div class="admin-form-group">
                <label>صور إضافية للمنطقة</label>
                <div id="images-container" class="dynamic-input-row">
                    <input type="file" name="images[]" accept="image/*">
                    <button type="button" class="add-row-btn" onclick="addInput(this)">+</button>
                </div>
            </div>

            <button id="addRegionSubmit" type="submit">إضافة المنطقة</button>

        </form>
    </main>

    <footer>  
       <p> استكشف جمال المملكة &copy; 2026</p>
    </footer>
    <script src="script.js"></script>

</body>
</html>