    // รับอ้างอิงแท็บที่คลิก
    const incomeTab = document.getElementById('income-tab');
    const dailyIncomeTab = document.getElementById('daily-income-tab');
    const apiTab = document.getElementById('api-tab');

    // รับอ้างอิงส่วนเนื้อหาที่เกี่ยวข้อง
    const incomeContent = document.getElementById('income-content');
    const dailyIncomeContent = document.getElementById('daily-income-content');
    const apiContent = document.getElementById('api-content');

    // ฟังก์ชันแสดงแท็บที่ถูกคลิกและซ่อนส่วนเนื้อหาที่ไม่ถูกคลิก
    function showTabContent(tab, content) {
        // ซ่อนส่วนเนื้อหาทั้งหมด
        incomeContent.style.display = 'none';
        dailyIncomeContent.style.display = 'none';
        apiContent.style.display = 'none';

        // แสดงส่วนเนื้อหาของแท็บที่ถูกคลิก
        content.style.display = 'block';

        // เน้นแท็บที่ถูกคลิก
        incomeTab.classList.remove('text-blue-700');
        dailyIncomeTab.classList.remove('text-blue-700');
        apiTab.classList.remove('text-blue-700');

        tab.classList.add('text-blue-700');
    }

    // เพิ่มการฟังก์ชันคลิกสำหรับแต่ละแท็บ
    incomeTab.addEventListener('click', function (e) {
        e.preventDefault();
        showTabContent(incomeTab, incomeContent);
    });

    dailyIncomeTab.addEventListener('click', function (e) {
        e.preventDefault();
        showTabContent(dailyIncomeTab, dailyIncomeContent);
    });

    apiTab.addEventListener('click', function (e) {
        e.preventDefault();
        showTabContent(apiTab, apiContent);
    });
// income.js
$(document).ready(function () {
    // รับอ้างอิงปุ่ม "ดูรายได้ประจำวัน"
    const showDailyIncomeButton = $('#show-daily-income');

    // รับอ้างอิง div ที่เกี่ยวข้องกับหน้า "รายได้ประจำวัน"
    const dailyIncomeContent = $('#daily-income-content');

    // เมื่อคลิกปุ่ม "ดูรายได้ประจำวัน"
    showDailyIncomeButton.click(function (e) {
        e.preventDefault();

        // แสดงหน้า "รายได้ประจำวัน" และซ่อนหน้าอื่น
        dailyIncomeContent.show();
        $('#income-content').hide();
        $('#api-content').hide();

        // ดึงข้อมูลรายได้ประจำวันและแสดงบนหน้า "รายได้ประจำวัน"
        $.get('daily-income-content.php', function (data) {
            dailyIncomeContent.html(data);
        });
    });
});

    // แสดงเนื้อหาแท็บเริ่มต้น
    showTabContent(incomeTab, incomeContent);
