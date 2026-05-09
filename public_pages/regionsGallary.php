<?php
include("../database_connection.php");
 ?> 
<!DOCTYPE html>
<html lang="ar" dir="rtl"> <head>
    <meta charset="UTF-8"> <title>معرض المناطق</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <header>
        <div>
            <nav>
                <ul>
                    <li> <a href="../index.php">الرئيسية</a></li>
                    <li> <a href="regionsGallary.php" class="active">معرض المناطق</a></li>
                    <li> <a href="../admin_pages/AdminLogin.php">دخول المشرف</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="gallery-page-main">
        <section class="filter-section">
            <input type="text" id="searchInput" placeholder="ابحث عن اسم المنطقة..." onkeyup="filterRegions()">

            <select id="locationFilter" onchange="filterRegions()">
                
                <option value="">جميع المواقع</option>
                <option value="شمال">شمال المملكة</option>
                <option value="جنوب">جنوب المملكة</option>
                <option value="شرق">شرق المملكة</option>
                <option value="غرب">غرب المملكة</option>
                <option value="وسط">وسط المملكة</option>
            </select>

            <select id="natureFilter" onchange="filterRegions()">
                
                <option value="">جميع أنواع الطبيعة</option>
                <option value="صحراوية">صحراوية</option>
                <option value="جبلية">جبلية</option>
                <option value="ساحلية">ساحلية</option>
                <option value="زراعية">زراعية/ريفية</option>
                <option value="بركانية">بركانية</option>
                <option value="جزيرة">جزيرة</option>
            </select>
        </section>

        <div class="regions_cards_container gallery-grid-container">
            <?php 
            $query="SELECT * FROM Regions";
            $regions = mysqli_query($conn,$query);

            if(mysqli_num_rows($regions)>0){
                while($region=mysqli_fetch_assoc($regions)){?>
                    <div class="region_card gallery-item-card">
                        <img src="../image/<?php echo $region['icon_path'];?>" alt="الصورة الرئيسية للمنطقة">
                        <div class="card_body">
                            <h2><?php echo $region['region_name']; ?></h2>
                            <p class="gallery-headline"><?php echo $region['headline']; ?></p>

                            <div class="loc_natu">
                                <span class="gallery-tag"><?php echo $region['location']." المملكة "; ?></span>
                                <span class="gallery-tag"><?php echo $region['nature']; ?></span>
                            </div>
                        </div>
                        <a href="regionDetails.php?id=<?php echo $region['region_id']; ?>" class="view-btn gallery-explore-btn">استكشف الآن</a>
                        
                    </div>
                <?php
                }
            }
            else{
                echo "<p class='no-data'>لا توجد مناطق لعرضها حالياً.</p>";
            } ?>
        </div>
    </main>

    <footer>  
        <p>استكشف جمال المملكة &copy; 2026</p>
    </footer>
    <script src=script.js></script>
</body>

</html>