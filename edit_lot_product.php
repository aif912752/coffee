<?php
require_once 'db.php';

if (isset($_GET['id_lot']) && is_numeric($_GET['id_lot'])) {
    $id_lot = $_GET['id_lot']; // แก้เป็น id_lot

    try {
        $stmt = $conn->prepare("SELECT lot.*, product.product_name FROM lot INNER JOIN product ON lot.id_product = product.id_product WHERE id_lot = :id_lot");
        $stmt->bindParam(':id_lot', $id_lot);
        $stmt->execute();

        $lot_data = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching lot data: " . $e->getMessage();
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn = null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <main class="flex min-h-screen flex-col justify-center bg-cyan-500 p-16">
        <h1 class="text-3xl font-bold text-white p-4 mb-8">แก้ไขข้อมูลสินค้า</h1>
        <div class="w-full rounded-xl bg-white p-4 shadow-2xl shadow-white/40">

            <form action="process_edit_lot.php" method="post">
                <div class="mb-4 grid grid-cols-2 gap-4 my-5 mx-5">
                    <div class="flex flex-col">
                        <label for="product_name" class="mb-2 font-semibold">ชื่อสินค้า</label>
                        <input type="text" id="product_name" name="product_name" value="<?php echo $lot_data['product_name']; ?>" class="w-full max-w-lg rounded-lg border border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40" readonly>
                    </div>
                    <div class="flex flex-col">
                        <label for="lot_date" class="mb-2 font-semibold">วันที่นำเข้า</label>
                        <input type="date" id="lot_date" name="lot_date" value="<?php echo $lot_data['lot_date']; ?>" class="w-full max-w-lg rounded-lg border border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">
                    </div>


                    <div class="flex flex-col">
                        <label for="lot_number" class="mb-2 font-semibold">จำนวนทั้งหมด</label>
                        <input type="text" id="lot_number" name="lot_number" value="<?php echo $lot_data['lot_number']; ?>" class="w-full max-w-lg rounded-lg border border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">
                    </div>

                    <div class="flex flex-col">
                        <label for="lot_cost" class="mb-2 font-semibold">ราคาต้นทุน</label>
                        <input type="text" id="lot_cost" name="lot_cost" value="<?php echo $lot_data['lot_cost']; ?>" class="w-full max-w-lg rounded-lg border border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">
                    </div>

                    <div class="flex flex-col">
                        <label for="lot_expiration" class="mb-2 font-semibold">วันหมดอายุ</label>
                        <input type="date" id="lot_expiration" name="lot_expiration" value="<?php echo $lot_data['lot_expiration']; ?>" class="w-full max-w-lg rounded-lg border border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">
                    </div>
                    <input type="hidden" name="id_lot" value="<?php echo $id_lot; ?>">
                </div>
                <div class="text-center min-h-[140px] w-full place-items-center overflow-x-scroll rounded-lg p-6 lg:overflow-visible">
                    <button type="submit" name="submit" class="middle none center mr-4 rounded-lg bg-green-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-green-500/20 transition-all hover:shadow-lg hover:shadow-green-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true">
                        บันทึก
                    </button>
                    <!-- Add a cancel link or button to go back to the product_list.php page -->
                    <a href="stock_product.php" class="middle none center mr-4 rounded-lg bg-red-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-red-500/20 transition-all hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true">
                        ยกเลิก
                    </a>
                </div>


            </form>
        </div>
    </main>
</body>

</html>
<!-- HTML Form to Edit Lot Data -->