function checkProductExpiration() {
  // ส่งคำขอไปยังเซิร์ฟเวอร์เพื่อตรวจสอบสินค้าใกล้หมดอายุ
  fetch("check_product_expiration.php")
    .then((response) => response.json())
    .then((data) => {
      const expiringLots = data.expiring_lots; // จำนวนล็อตที่ใกล้หมดอายุทั้งหมด
      const expirationCountElementMain = document.getElementById("expiration-count-main");
      const expirationCountElementSub = document.getElementById("expiration-count-sub");
      
      if (expiringLots > 0) {
        // แสดงจำนวนล็อตที่ใกล้หมดอายุใน div สำหรับเมนูหลักและเมนูย่อย
        expirationCountElementMain.textContent = expiringLots;
        expirationCountElementSub.textContent = expiringLots;
        expirationCountElementMain.style.display = "block";
        expirationCountElementSub.style.display = "block";
      } else {
        // ซ่อน div สำหรับเมนูหลักและเมนูย่อย ถ้าไม่มีล็อตใกล้หมดอายุ
        expirationCountElementMain.style.display = "none";
        expirationCountElementSub.style.display = "none";
      }
    })
    .catch((error) => {
      console.error("เกิดข้อผิดพลาดในการตรวจสอบสินค้า:", error);
    });
}

// เรียกใช้งานฟังก์ชันตรวจสอบสินค้าใกล้หมดอายุ
checkProductExpiration();