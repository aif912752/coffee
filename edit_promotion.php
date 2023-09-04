<?php

require_once('db.php');

if (isset($_GET['id_promotion'])) {
    $id_promotion = $_GET['id_promotion'];

    try {
        $stmt = $conn->prepare("SELECT * FROM promotion WHERE id_promotion = :id_promotion");
        $stmt->bindParam(':id_promotion', $id_promotion);
        $stmt->execute();
        $promotion = $stmt->fetch(PDO::FETCH_ASSOC);
        $promotion_name = $promotion['promotion_name'];
        $promotion_date = $promotion['promotion_date'];
        $promotion_end = $promotion['promotion_end'];
        $discount_amount = $promotion['discount_amount'];
        $discount_type = $promotion['discount_type'];

        // Fetch the participating_products column as a string from the database
        $participating_products_string = $promotion['participating_products'];

        // Convert the string into an array by exploding using the comma delimiter
        $participating_products = explode(',', $participating_products_string);

        // Fetch the products from the database
        $stmt_products = $conn->query("SELECT * FROM product");
        $products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>
<!-- ตรวจสอบค่าที่ถูกเก็บในตัวแปร $participating_products -->


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

            <form action="process_update_promotion.php" method="post">
                <input type="hidden" name="id_promotion" value="<?php echo $id_promotion; ?>">
                <div class="mb-4 grid grid-cols-2 gap-4 my-5 mx-5">
                    <div class="flex flex-col">
                        <label for="promotion_name" class="mb-2 font-semibold">ชื่อโปรโมชั่น</label>
                        <input type="text" id="promotion_name" name="promotion_name" value="<?php echo $promotion_name; ?>" class="w-full max-w-lg rounded-lg border border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">
                    </div>

                    <div class="flex flex-col">
                        <label for="discount_amount" class="mb-2 font-semibold">ยอดลดราคา</label>
                        <input type="number" id="discount_amount" name="discount_amount" value="<?php echo $discount_amount; ?>" class="w-full max-w-lg rounded-lg border border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">
                    </div>

                    <div class="flex flex-col">
                        <label for="discount_type" class="font-semibold mb-2">รูปแบบการลดราคา</label>
                        <div class="flex items-center">
                            <input type="radio" id="discount_type_percentage" name="discount_type" value="percentage" class="mr-4 ml-2" <?php if ($discount_type === 'percentage') echo "checked"; ?>>
                            <label for="discount_type_percentage" class="">เปอร์เซ็นต์</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="discount_type_amount" name="discount_type" value="amount" class="mr-4 ml-2" <?php if ($discount_type === 'amount') echo "checked"; ?>>
                            <label for="discount_type_amount">จำนวนเงิน</label>
                        </div>
                    </div>


                    <div class="flex flex-col">
                        <label for="promotion_date" class="mb-2 font-semibold">วันที่จัดโปรโมชั่น</label>
                        <input type="date" id="promotion_date" name="promotion_date" value="<?php echo $promotion_date; ?>" class="w-full max-w-lg rounded-lg border border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">
                    </div>


                    <div class="flex flex-col">
                        <label for="promotion_end" class="mb-2 font-semibold">วันที่หมดโปรโมชัน</label>
                        <input type="date" id="promotion_end" name="promotion_end" value="<?php echo $promotion['promotion_end']; ?>" class="w-full max-w-lg rounded-lg border border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40">
                    </div>

                    <div class="flex flex-col">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-semibold mb-2 pr-6">
                            สินค้าที่ร่วมรายการ
                        </label>
                        <div class="flex flex-wrap items-center pr-6 space-x-2">
                            <?php foreach ($products as $product) : ?>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="participating_products[]" id="product-<?php echo $product['id_product']; ?>" class="peer hidden" value="<?php echo $product['product_name']; ?>" <?php if (in_array($product['product_name'], $participating_products)) echo "checked"; ?> />
                                    <label for="product-<?php echo $product['id_product']; ?>" class="select-none cursor-pointer rounded-lg border-2 border-slate-600 py-1 px-5 font-bold text-black transition-colors duration-200 ease-in-out peer-checked:bg-black peer-checked:text-white peer-checked:border-slate-600">
                                        <?php echo $product['product_name']; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>


                </div>
                <div class="text-center min-h-[140px] w-full place-items-center overflow-x-scroll rounded-lg p-6 lg:overflow-visible">
                    <button type="submit" name="submit" class="middle none center mr-4 rounded-lg bg-green-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-green-500/20 transition-all hover:shadow-lg hover:shadow-green-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true">
                        บันทึก
                    </button>
                    <!-- Add a cancel link or button to go back to the product_list.php page -->
                    <a href="promotion.php" class="middle none center mr-4 rounded-lg bg-red-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-red-500/20 transition-all hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true">
                        ยกเลิก
                    </a>
                </div>

            </form>
        </div>
    </main>
</body>

</html>