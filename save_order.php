<?php
@session_start();
header("Content-Type: application/json");
// เชื่อมต่อฐานข้อมูล
require_once('db.php');
$data = json_decode(file_get_contents('php://input'));
try {
    // รับข้อมูลการสั่งซื้อจาก Request
    // $data = json_decode(file_get_contents('php://input'), true);
    // $data = json_decode($_POST);

    // สามารถทำการประมวลผลข้อมูลและบันทึกลงในตารางใบเสร็จได้ตามความเหมาะสม
    $id_admin = $_SESSION['user_id']; // รหัสผู้ดูแลระบบที่เข้าสู่ระบบ
    $datetime = date('Y-m-d H:i:s'); // วันที่และเวลาปัจจุบัน

    // บันทึกข้อมูลใบเสร็จลงในตาราง invoice
    $totalPrice = $data->totalPrice;
    $cartItems = $data->cartItems;

    $cartItemsJson = json_encode($cartItems);

    $err = 0;

    foreach ($cartItems as $p) {
        $qty = $p->qty;
        $p_sql = "UPDATE lot SET lot_number=lot_number-$qty WHERE id_product=:product_id AND lot_number >0  ORDER BY lot_date ASC LIMIT 1;";
        $stmt = $conn->prepare($p_sql);
        $stmt->bindParam(':product_id', $p->product_id);
        try {
            $stmt->execute([':product_id' => $p->id]);
        } catch (PDOException $e) {
            echo $e->getMessage();
            $err++;
        }
    }
    if ($err > 0) {
        http_response_code(404);
    }
    if ($err == 0) {
        //   บันทึกข้อมูลใบเสร็จลงในตาราง invoice
        $insertInvoiceQuery = "INSERT INTO invoice (datetime, all_money, id_admin, cart_items) VALUES (:datetime, :all_money, :id_admin, :cart_items)";
        $insertInvoiceStatement = $conn->prepare($insertInvoiceQuery);
        $insertInvoiceStatement->execute(array(
            ':datetime' => $datetime,
            ':all_money' => $totalPrice,
            ':id_admin' => $id_admin,
            ':cart_items' => $cartItemsJson
        ));
        http_response_code(200);
        echo json_encode(["success" => true]);
    }
} catch (PDOException $e) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// ส่งผลลัพธ์กลับเป็น JSON
