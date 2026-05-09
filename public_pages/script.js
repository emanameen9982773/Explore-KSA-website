function filterRegions() {
    // جلب قيم المدخلات
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const locationValue = document.getElementById('locationFilter').value;
    const natureValue = document.getElementById('natureFilter').value;
    
    // جلب جميع بطاقات المناطق
    const cards = document.querySelectorAll('.gallery-item-card');

    cards.forEach(card => {
        // استخراج النصوص من البطاقة للبحث فيها
        const regionName = card.querySelector('h2').textContent.toLowerCase();
        const tags = card.querySelectorAll('.gallery-tag');
        const cardLocation = tags[0].textContent; // أول تاغ هو الموقع
        const cardNature = tags[1].textContent;   // ثاني تاغ هو الطبيعة

        // شروط الفلترة
        const matchesSearch = regionName.includes(searchValue);
        const matchesLocation = locationValue === "" || cardLocation.includes(locationValue);
        const matchesNature = natureValue === "" || cardNature.includes(natureValue);

        // إظهار أو إخفاء البطاقة بناءً على الشروط
        if (matchesSearch && matchesLocation && matchesNature) {
            card.style.display = "flex";
        } else {
            card.style.display = "none";
        }
    });
}