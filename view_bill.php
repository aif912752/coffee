<?php
// เชื่อมต่อฐานข้อมูล
require_once('db.php');

// ตรวจสอบว่ามีค่า id_invoice ที่ถูกส่งมาทาง URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // รับค่า ID ของใบเสร็จที่ต้องการแสดงรายละเอียด
    $invoiceId = $_GET['id'];

    // สร้างคำสั่ง SQL เพื่อดึงข้อมูลใบเสร็จและ username
    $sql = "SELECT i.*, a.username AS username
            FROM invoice i
            JOIN admin a ON i.id_admin = a.id_admin
            WHERE i.id_invoice = :invoice_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':invoice_id', $invoiceId);
    $stmt->execute();

    // ตรวจสอบว่าพบข้อมูลหรือไม่
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // หากไม่พบใบเสร็จที่ตรงกับ ID ที่ระบุใน URL
        echo "ไม่พบใบเสร็จที่คุณร้องขอ";
        exit;
    }
} else {
    // หากไม่ได้ระบุ ID ใน URL
    echo "กรุณาระบุ ID ใบเสร็จที่คุณต้องการดูรายละเอียด";
    exit;
}
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดใบเสร็จ</title>
    <!-- เรียกใช้ไฟล์ CSS ของ Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container justify-center mt-5">
        <div class="card cart">
            <label class="title">ใบเสร็จรับเงิน</label>
            <div class="steps">
                <div class="step">
                    <div>
                        <span>วันที่: <?= $row['datetime'] ?></span>
                        <p>พนักงาน: <?= $row['username'] ?></p>
                    </div>
                    <hr>
                    <div class="payments">
                        <span>รายการสินค้า</span>
                        <div class="details">
                            <?php
                            $cartItems = json_decode($row['cart_items'], true);
                            foreach ($cartItems as $item) {
                            ?>
                                <span><?= $item['product_name'] ?> x <?= $item['qty'] ?></span>
                                <?php
                                // ตรวจสอบว่ารายการสินค้านี้มีโปรโมชั่น
                                if (!empty($item['promotion_name'])) {
                                ?>
                                    <span><?= $item['discount_name'] ?></span>
                                    <span> : <?= $item['price'] - $item['discount_amount'] ?> </span>
                                <?php
                                } else {
                                    // ถ้าไม่มีโปรโมชั่นให้แสดงราคาเดิม
                                ?>
                                    <span><?= $item['price'] ?> </span>
                            <?php
                                }
                            }
                            ?>


                            <?php
                            if (!empty($row['promotions'])) {
                                $promotionIds = json_decode($row['promotions'], true);
                                $promotionNames = array(); // เก็บชื่อโปรโมชั่น

                                // วนลูปเพื่อดึงชื่อโปรโมชั่นจากฐานข้อมูล
                                foreach ($promotionIds as $promotionId) {
                                    // ดึงชื่อโปรโมชั่นจากตาราง promotions โดยใช้ id_promotion
                                    $promotionQuery = "SELECT promotion_name FROM promotion WHERE id_promotion = :promotion_id";
                                    $promotionStmt = $conn->prepare($promotionQuery);
                                    $promotionStmt->bindParam(':promotion_id', $promotionId);
                                    $promotionStmt->execute();
                                    $promotionRow = $promotionStmt->fetch(PDO::FETCH_ASSOC);
                                    if ($promotionRow) {
                                        $promotionNames[] = $promotionRow['promotion_name'];
                                    }
                                }

                                // แสดงชื่อโปรโมชั่นที่ดึงมา
                                if (!empty($promotionNames)) {
                            ?>
                                    <span> <?= implode(' <span> </span> ',   $promotionNames) ?></span> <span> </span>
                                <?php
                                } else {
                                ?>
                                    <p class="mb-2">ไม่มีโปรโมชั่น</p>
                            <?php
                                }
                            }
                            ?>
                            <span>รวม</span>
                            <span><?= $row['all_money'] ?> </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card checkout">
            <div class="footer">
                <label class="price">$<?= $row['all_money'] ?> บาท</label>
                <a href="javascript:window.print()">
                    <button href="javascript:window.print()" class="checkout-btn">พิมพ์</button>
                </a>
            </div>
        </div>
    </div>
</body>

</html>





<style>
    /* Body */
    .container {
        display: grid;
        grid-template-columns: auto;
    }

    hr {
        height: 1px;
        background-color: rgba(16, 86, 82, .75);
        ;
        border: none;
    }

    .card {
        width: 400px;
        background: rgb(255, 250, 235);
        box-shadow: 0px 187px 75px rgba(0, 0, 0, 0.01), 0px 105px 63px rgba(0, 0, 0, 0.05), 0px 47px 47px rgba(0, 0, 0, 0.09), 0px 12px 26px rgba(0, 0, 0, 0.1), 0px 0px 0px rgba(0, 0, 0, 0.1);
    }

    .title {
        width: 100%;
        height: 40px;
        position: relative;
        display: flex;
        align-items: center;
        padding-left: 20px;
        border-bottom: 1px solid rgba(16, 86, 82, .75);
        ;
        font-weight: 700;
        font-size: 11px;
        color: #000000;
    }

    /* Cart */
    .cart {
        border-radius: 19px 19px 0px 0px;
    }

    .cart .steps {
        display: flex;
        flex-direction: column;
        padding: 20px;
    }

    .cart .steps .step {
        display: grid;
        gap: 10px;
    }

    .cart .steps .step span {
        font-size: 13px;
        font-weight: 600;
        color: #000000;
        margin-bottom: 8px;
        display: block;
    }

    .cart .steps .step p {
        font-size: 11px;
        font-weight: 600;
        color: #000000;
    }

    /* Promo */
    .promo form {
        display: grid;
        grid-template-columns: 1fr 80px;
        gap: 10px;
        padding: 0px;
    }

    .input_field {
        width: auto;
        height: 36px;
        padding: 0 0 0 12px;
        border-radius: 5px;
        outline: none;
        border: 1px solid rgb(16, 86, 82);
        background-color: rgb(251, 243, 228);
        transition: all 0.3s cubic-bezier(0.15, 0.83, 0.66, 1);
    }

    .input_field:focus {
        border: 1px solid transparent;
        box-shadow: 0px 0px 0px 2px rgb(251, 243, 228);
        background-color: rgb(201, 193, 178);
    }

    .promo form button {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        padding: 10px 18px;
        gap: 10px;
        width: 100%;
        height: 36px;
        background: rgba(16, 86, 82, .75);
        box-shadow: 0px 0.5px 0.5px #F3D2C9, 0px 1px 0.5px rgba(239, 239, 239, 0.5);
        border-radius: 5px;
        border: 0;
        font-style: normal;
        font-weight: 600;
        font-size: 12px;
        line-height: 15px;
        color: #000000;
    }

    /* Checkout */
    .payments .details {
        display: grid;
        grid-template-columns: 10fr 1fr;
        padding: 0px;
        gap: 5px;
    }

    .payments .details span:nth-child(odd) {
        font-size: 12px;
        font-weight: 600;
        color: #000000;
        margin: auto auto auto 0;
    }

    .payments .details span:nth-child(even) {
        font-size: 13px;
        font-weight: 600;
        color: #000000;
        margin: auto 0 auto auto;
    }

    .checkout .footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 10px 10px 20px;
        background-color: rgba(16, 86, 82, .5);
    }

    .price {
        position: relative;
        font-size: 22px;
        color: #2B2B2F;
        font-weight: 900;
    }

    .checkout .checkout-btn {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        width: 150px;
        height: 36px;
        background: rgba(16, 86, 82, .55);
        box-shadow: 0px 0.5px 0.5px rgba(16, 86, 82, .75), 0px 1px 0.5px rgba(16, 86, 82, .75);
        ;
        border-radius: 7px;
        border: 1px solid rgb(16, 86, 82);
        ;
        color: #000000;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.15, 0.83, 0.66, 1);
    }
</style>