<?php
require_once('db.php'); // Include the database connection file

// Retrieve promotion data from the database
// ดึงข้อมูล promotion จากฐานข้อมูล
try {
    $sql = "SELECT * FROM promotion";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ตรวจสอบว่าตัวแปร $participating_products ถูกประกาศและกำหนดค่าให้ตั้งแต่ต้นหรือไม่
    if (!isset($participating_products)) {
        $participating_products = array(); // กำหนดให้เป็นอาร์เรย์เปล่าไว้ก่อน
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// ดึงข้อมูล product จากฐานข้อมูล
try {
    $sql = "SELECT id_product, product_name FROM product";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
<!-- ตรวจสอบค่าตัวแปรและข้อมูลที่ใช้ในการแสดงผล -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <?php
    // นำไฟล์ sidebar เข้ามา
    include 'sidebar.html';
    ?>
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4 ">
        <!-- component -->
        <section class="container px-4 mx-auto">

            <div class="mt-6 md:flex md:items-center md:justify-between ">
                <button id="addPromotionButton" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white duration-200 bg-[#557A46] rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-[#41644A] ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>เพิ่มโปรโมชั่น</span>
                </button>
                <!-- ส่วน Modal HTML -->
                <div class="hidden " id="addPromotionModal">
                    <div class=" fixed left-0 top-0 h-full w-full items-center justify-center bg-white bg-opacity-50 py-10" id="addPromotionModal">
                        <div class="flex justify-center items-end text-center min-h-screen sm:block">
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">​</span>
                            <div class="inline-block text-left bg-gradient-to-r from-rose-100 to-teal-100 rounded-lg overflow-hidden align-bottom transition-all transform
                                        shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                                <div class="items-center w-full mr-auto ml-auto relative max-w-7xl md:px-12 lg:px-24">
                                    <p class="mt-8 mb-5 text-2xl font-semibold leading-none tracking-tighter lg:text-3xl text-center">
                                        เพิ่มโปรโมชั่น</p>
                                    <form action="process_add_promotion.php" method="post">
                                        <div class="mt-4 mr-auto mb-4 ml-auto bg-whi max-w-lg">
                                            <div class="flex flex-col pr-6 pl-6">
                                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2 ">
                                                    ชื่อโปรโมชั่น
                                                </label>
                                                <input type="text" name="promotion_name" class="w-full px-6 py-3 mb-2 border border-slate-600 rounded-lg font-medium " value="" />
                                            </div>
                                            <!-- ส่วนเพิ่มข้อมูลการลดราคา -->
                                            <div class="flex flex-col pr-6 pl-6">
                                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2 ">
                                                    ยอดลดราคา
                                                </label>
                                                <input type="number" name="discount_amount" class="w-full px-6 py-3 mb-2 border border-slate-600 rounded-lg font-medium" value="" />
                                            </div>
                                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2 pr-6 pl-6">
                                                รูปแบบการลดราคา
                                            </label>
                                            <div class="flex flex-row pr-6 pl-6">

                                                <label class=" uppercase tracking-wide text-grey-darker text-xs font-bold mb-2 flex items-center mr-4">
                                                    <input type="radio" name="discount_type" value="percentage" class="mr-1 border border-slate-600 rounded-lg" />
                                                    เปอร์เซ็นต์
                                                </label>
                                                <label class=" uppercase tracking-wide text-grey-darker text-xs font-bold mb-2 flex items-center">
                                                    <input type="radio" name="discount_type" value="amount" class="mr-1 border border-slate-600 rounded-lg" />
                                                    ราคาจริง
                                                </label>
                                            </div>


                                            <div class="flex flex-col pr-6 pl-6">
                                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2 ">
                                                    วันที่จัดโปรโมชัน
                                                </label>
                                                <input type="date" name="promotion_date" class="w-full px-6 py-3 mb-2 border border-slate-600 rounded-lg font-medium " value="" />

                                            </div>
                                            <div class="flex flex-col pr-6 pl-6">
                                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2 ">
                                                    วันที่หมดโปรโมชัน
                                                </label>
                                                <input type="date" name="promotion_end" class="w-full px-6 py-3 mb-2 border border-slate-600 rounded-lg font-medium " value="" />
                                            </div>

                                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2 pr-6 pl-6">
                                                สินค้าที่ร่วมรายการ
                                            </label>

                                            <div class="flex flex-wrap  items-center pr-6 pl-6 space-x-2">

                                                <?php foreach ($products as $product) : ?>
                                                    <div class="flex items-center mt-2">
                                                        <input type="checkbox" name="participating_products[]" id="product-<?php echo $product['id_product']; ?>" class="peer hidden" value="<?php echo $product['id_product']; ?>" />
                                                        <label for="product-<?php echo $product['id_product']; ?>" class="select-none cursor-pointer rounded-lg border-2 border-slate-600 py-1 px-5 font-bold text-black transition-colors duration-200  ease-in-out peer-checked:bg-black peer-checked:text-white peer-checked:border-slate-600">
                                                            <?php echo $product['product_name']; ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                            <div class="mt-6 mb-6 flex items-center justify-center  ">
                                                <button type="submit" class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-green-600">บันทึก</button>
                                                <button type="button" class="bg-gradient-to-r from-red-400 to-red-600 text-white px-4 py-2 rounded ml-2 hover:bg-red-800" onclick="closesModal()">ยกเลิก</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    // เมื่อปุ่ม "เพิ่มโปรโมชั่น" ถูกคลิก
                    document.getElementById("addPromotionButton").addEventListener("click", function() {
                        var modal = document.getElementById("addPromotionModal");
                        modal.classList.remove("hidden");
                    });

                    // ฟังก์ชันสำหรับปิด Modal
                    function closesModal() {
                        var modal = document.getElementById("addPromotionModal");
                        modal.classList.add("hidden");
                    }

                    // ฟังก์ชันสำหรับปิด Modal
                    function closesModal() {
                        var modal = document.getElementById("addPromotionModal");
                        modal.classList.add("hidden");
                    }
                </script>
            </div>


            <div class="flex flex-col mt-6 ">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8 shadow-md">
                        <div class="overflow-hidden  dark:border-gray-700 md:rounded-lg border border-gray-200 shadow-md">
                            <table class="min-w-full divide-y rounded-lg border border-gray-200 shadow-md text-gray-500">
                                <thead class="bg-[#E1ECC8]">
                                    <tr class=>
                                        <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-900 dark:text-gray-900">
                                            <button class="flex items-center gap-x-3 focus:outline-none">
                                                <span>ชื่อโปรโมชั่น</span>
                                            </button>
                                        </th>
                                        <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-900 dark:text-gray-900">
                                            วันที่จัดโปรโมชัน
                                        </th>
                                        <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-900 dark:text-gray-900">
                                            วันที่หมดโปรโมชัน
                                        </th>
                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-900 ">สินค้าที่ร่วมรายการ</th>
                                        <th class="bg-[#E1ECC8]"> </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 ">
                                    <?php
                                    foreach ($promotions as $promotion) {
                                        $participating_products = explode(',', $promotion['participating_products']);
                                    ?>
                                        <tr>
                                            <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                                <div>
                                                    <p class="text-sm font-normal text-gray-600 dark:text-gray-900"><?php echo $promotion['promotion_name']; ?></p>
                                                </div>
                                            </td>
                                            <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                                <div class="inline px-3 py-1 text-xs font-medium rounded-full text-[#0B2B26] gap-x-2 bg-[#E1ECC8]">
                                                    <?php echo $promotion['promotion_date']; ?>

                                                </div>
                                            </td>
                                            <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                                <div class="inline px-3 py-1 text-xs font-medium rounded-full text-[#0B2B26] gap-x-2  bg-[#E1ECC8]">
                                                    <?php echo $promotion['promotion_end']; ?>

                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-sm whitespace-nowrap">
                                                <?php
                                                $participating_products_array = explode(',', $promotion['participating_products']);

                                                foreach ($participating_products_array as $product_name) {
                                                    foreach ($products as $product) {
                                                        if ($product['product_name'] == $product_name) {
                                                            echo '<span class="inline px-3 py-1 mx-2 text-xs font-medium rounded-full text-[#0B2B26] gap-x-2  bg-[#E1ECC8]">' . $product['product_name'] . '</span>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>

                                            <td class="px-4 py-4 text-sm whitespace-nowrap ">
                                                <div class="flex gap-4">
                                                    <a class="openModal" href="#" data-promotion-id="<?php echo $promotion['id_promotion']; ?>" onclick="openDeleteModal(<?php echo $promotion['id_promotion']; ?>)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6" x-tooltip="tooltip">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </a>
                                                    <!-- ส่วน Modal HTML -->
                                                    <div class="modal hidden fixed inset-0 justify-center items-center" id="deleteModal">
                                                        <!-- component -->
                                                        <div class="min-w-screen h-screen animated fadeIn faster fixed left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover">
                                                            <div class="absolute inset-0 z-0"></div>
                                                            <div class="w-full max-w-lg p-5 relative mx-auto my-auto rounded-xl shadow-lg bg-slate-100">
                                                                <!--content-->
                                                                <div class="">
                                                                    <!--body-->
                                                                    <div class="text-center p-5 flex-auto justify-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 -m-1 flex items-center text-red-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                        </svg>
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 flex items-center text-red-500 mx-auto" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                                        </svg>
                                                                        <h2 class="text-xl font-bold py-4">ยืนยันลบสินค้า</h3>
                                                                    </div>
                                                                    <!--footer-->
                                                                    <div class="p-3 mt-2 text-center space-x-4 md:block">
                                                                        <button id="cancelButton" class="mb-2 md:mb-0 bg-white px-5 py-2 text-sm shadow-sm font-medium tracking-wider border text-gray-600 rounded-full hover:shadow-lg hover:bg-gray-100">
                                                                            ยกเลิก
                                                                        </button>
                                                                        <!-- ปุ่ม "ลบ" ใน modal -->
                                                                        <button class="bg-red-500 border border-red-500 px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-white rounded-full hover:shadow-lg hover:bg-red-600" id="confirmButton">
                                                                            ลบ
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <a href="edit_promotion.php?id_promotion=<?php echo $promotion['id_promotion']; ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6" x-tooltip="tooltip">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                        </svg>
                                                    </a>

                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </section>
    </div>


</body>
<!-- JavaScript -->
<script>
    // ฟังก์ชันสำหรับลบสินค้า
    function deletePromotionId(promotionId) {
        // ทำการลบสินค้าด้วยข้อมูล promotionId ที่ได้รับ
        $.ajax({
            type: "POST",
            url: "delete_promotion.php", // URL หรือ API ที่ใช้ในการลบสินค้า
            data: {
                promotionId: promotionId
            }, // ส่งค่า promotionId ไปให้กับ API
            success: function(response) {
                // ดำเนินการหลังจากลบสินค้าเสร็จสิ้น
                // ตัวอย่างเช่น แสดงข้อความหรือทำการรีเฟรชตารางสินค้าเพื่อแสดงข้อมูลใหม่

                // ซ่อน Modal
                var modal = document.getElementById("deleteModal");
                modal.classList.add("hidden");

                // รีเฟรชหน้าเว็บหรือดำเนินการอื่นๆ ตามที่ต้องการ
                // ตัวอย่างเช่น รีเฟรชตารางสินค้าเพื่อแสดงข้อมูลใหม่
                location.reload();
            },
            error: function(xhr, status, error) {
                // แสดงข้อความหรือแจ้งเตือนหากเกิดข้อผิดพลาดในการลบสินค้า
                alert("Error: " + xhr.responseText);
            }
        });
    }

    // เปิด Confirmation Modal เมื่อคลิกที่ปุ่ม "รูปไอคอน delete" หรือที่ปุ่มที่ใช้เปิด Modal
    function openDeleteModal(promotionId) {
        var modal = document.getElementById("deleteModal");
        modal.classList.remove("hidden");

        // ส่ง product_id ไปยังปุ่ม "ลบ" ใน Modal เพื่อนำไปใช้ในการลบสินค้า
        var confirmButton = document.getElementById("confirmButton");
        confirmButton.setAttribute("data-promotion-id", promotionId);
        confirmButton.onclick = function() {
            deletePromotionId(promotionId);
        };
    }

    // กำหนดเหตุการณ์เมื่อคลิกที่ปุ่ม "ยกเลิก" ให้ปิด Modal
    var cancelButton = document.getElementById("cancelButton");
    cancelButton.onclick = function() {
        closeModal();
    };

    function closeModal() {
        var modal = document.getElementById("deleteModal");
        modal.classList.add("hidden");
    }
</script>


</html>
<!-- ตัวเลื่อนเมนู sidebar -->
<script>
    const sidebar = document.querySelector("aside");
    const maxSidebar = document.querySelector(".max")
    const miniSidebar = document.querySelector(".mini")
    const roundout = document.querySelector(".roundout")
    const maxToolbar = document.querySelector(".max-toolbar")
    const logo = document.querySelector('.logo')
    const content = document.querySelector('.content')
    const moon = document.querySelector(".moon")
    const sun = document.querySelector(".sun")

    function setDark(val) {
        if (val === "dark") {
            document.documentElement.classList.add('dark')
            moon.classList.add("hidden")
            sun.classList.remove("hidden")
        } else {
            document.documentElement.classList.remove('dark')
            sun.classList.add("hidden")
            moon.classList.remove("hidden")
        }
    }

    function openNav() {
        if (sidebar.classList.contains('-translate-x-48')) {
            // max sidebar 
            sidebar.classList.remove("-translate-x-48")
            sidebar.classList.add("translate-x-none")
            maxSidebar.classList.remove("hidden")
            maxSidebar.classList.add("flex")
            miniSidebar.classList.remove("flex")
            miniSidebar.classList.add("hidden")
            maxToolbar.classList.add("translate-x-0")
            maxToolbar.classList.remove("translate-x-24", "scale-x-0")
            logo.classList.remove("ml-12")
            content.classList.remove("ml-12")
            content.classList.add("ml-12", "md:ml-60")
        } else {
            // mini sidebar
            sidebar.classList.add("-translate-x-48")
            sidebar.classList.remove("translate-x-none")
            maxSidebar.classList.add("hidden")
            maxSidebar.classList.remove("flex")
            miniSidebar.classList.add("flex")
            miniSidebar.classList.remove("hidden")
            maxToolbar.classList.add("translate-x-24", "scale-x-0")
            maxToolbar.classList.remove("translate-x-0")
            logo.classList.add('ml-12')
            content.classList.remove("ml-12", "md:ml-60")
            content.classList.add("ml-12")

        }

    }
</script>