// สร้างฟังก์ชันสำหรับตรวจสอบสินค้าใกล้หมดอายุ
function checkProductExpiration() {
    // ส่งคำขอไปยังเซิร์ฟเวอร์เพื่อตรวจสอบสินค้าใกล้หมดอายุ
    fetch("check_product_expiration.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.alert) {
          // แสดงข้อความ Alert หากมีสินค้าใกล้หมดอายุ
          showExpirationAlert(data.productName); // ส่งชื่อสินค้ามาแสดง
        } else {
          // ซ่อนข้อความ Alert ถ้าไม่มีสินค้าใกล้หมดอายุ
          hideExpirationAlert();
        }
      })
      .catch((error) => {
        console.error("เกิดข้อผิดพลาดในการตรวจสอบสินค้า:", error);
      });
}

// ฟังก์ชันแสดงข้อความ Alert
function showExpirationAlert(productName) {
    const alertElement = document.getElementById("expiration-alert");
    const messageElement = document.getElementById("expiration-message");
    messageElement.textContent = `สินค้า ${productName} ใกล้หมดอายุภายในสามวัน`;
    alertElement.style.display = "block";
}

// ฟังก์ชันซ่อนข้อความ Alert
function hideExpirationAlert() {
    const alertElement = document.getElementById("expiration-alert");
    alertElement.style.display = "none";
}

// เรียกใช้งานฟังก์ชันตรวจสอบสินค้าใกล้หมดอายุ
checkProductExpiration();
