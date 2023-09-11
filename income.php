<?php
// เชื่อมต่อฐานข้อมูล
require_once('db.php');

// สร้างคำสั่ง SQL เพื่อดึงข้อมูลใบเสร็จ
$sql = "SELECT i.*, a.username AS username
        FROM invoice i
        JOIN admin a ON i.id_admin = a.id_admin
        WHERE DATE(i.datetime) = CURDATE()"; // ดึงใบเสร็จที่มีวันที่เป็นปัจจุบัน
$stmt = $conn->prepare($sql);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สรุปรายได้ประจำวัน</title>
    <!-- เรียกใช้ไฟล์ CSS ของ Tailwind CSS หรือสไตล์อื่น ๆ ตามที่คุณต้องการ -->
</head>
<body>
    <h1 class="text-2xl font-semibold mb-4">สรุปรายได้ประจำวัน</h1>

    <!-- สร้างตารางเพื่อแสดงข้อมูลใบเสร็จ -->
    <table class="border-collapse w-full">
        <thead>
            <tr>
                <th class="border border-gray-400 px-4 py-2">ID ใบเสร็จ</th>
                <th class="border border-gray-400 px-4 py-2">วันที่</th>
                <th class="border border-gray-400 px-4 py-2">พนักงาน</th>
                <th class="border border-gray-400 px-4 py-2">ราคารวม</th>
            </tr>
        </thead>
        <tbody>
            <!-- วนลูปเรียกข้อมูลใบเสร็จทั้งหมดและแสดงในตาราง -->
            <?php
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td class="border border-gray-400 px-4 py-2">' . $row['id_invoice'] . '</td>';
                    echo '<td class="border border-gray-400 px-4 py-2">' . $row['datetime'] . '</td>';
                    echo '<td class="border border-gray-400 px-4 py-2">' . $row['username'] . '</td>';
                    echo '<td class="border border-gray-400 px-4 py-2">' . $row['all_money'] . ' บาท</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td class="border border-gray-400 px-4 py-2" colspan="4">ไม่พบข้อมูลใบเสร็จประจำวัน</td></tr>';
            }
            ?>
        </tbody>
    </table>
</body>
</html>
