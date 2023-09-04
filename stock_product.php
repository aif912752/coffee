<?php
// นำไฟล์ sidebar เข้ามา
include 'sidebar.html';

// เรียกใช้ไฟล์ db.php เพื่อเชื่อมต่อกับฐานข้อมูล
require_once 'db.php';

// ตรวจสอบข้อมูลล็อตสินค้าและชื่อสินค้า และรูปภาพจากฐานข้อมูล
try {
    $sql = "SELECT lot.id_lot, product.id_product, product.product_name, product.product_img, lot.lot_date, lot.lot_number, lot.lot_cost, lot.lot_expiration
            FROM lot
            INNER JOIN product ON lot.id_product = product.id_product
            ORDER BY lot.id_lot DESC";

    $stmt = $conn->query($sql);

    $lot_data = array();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $lot_data[] = $row;
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
// ตรวจสอบข้อมูลสินค้าจากตาราง product
try {
    $sql = "SELECT id_product, product_name FROM product";

    $stmt = $conn->query($sql);

    $product_data = array();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product_data[] = $row;
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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
    <link rel="stylesheet" type="text/css" href="css/button.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</head>

<body>
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4 ">
        <buttons id="buttonmodal" type="button" class="flex items-center justify-center"> เพิ่มล็อตสินค้า
        </buttons>

        <!--Open modal button-->
        <div id="modal" class="fixed top-0 left-0 w-screen h-screen flex items-center justify-center bg-blue-500 bg-opacity-50 transform scale-0 transition-transform duration-300">
            <!-- Modal content -->
            <div class="bg-white w-1/2 h-1/2 p-12">
                <!--Close modal button-->
                <form method="post" action="add_lot.php">
                    <!-- Test content -->
                    <div class="-mx-3 md:flex mb-6">
                        <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-product">
                                ชื่อสินค้า
                            </label>
                            <select class="block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3" id="grid-product" name="product">
                                <option value="" disabled selected>เลือกสินค้า</option>
                                <?php foreach ($product_data as $product) : ?>
                                    <?php
                                    $product_id = $product['id_product'];
                                    $product_name = $product['product_name'];
                                    ?>
                                    <option value="<?php echo $product_id; ?>"><?php echo $product_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">
                                วันที่นำเข้า
                            </label>
                            <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" type="date" name="lot_date">
                        </div>


                    </div>

                    <div class="-mx-3 md:flex mb-2">
                        <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-city">
                                จำนวนทั้งหมด
                            </label>
                            <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" id="grid-zip" type="number" name="lot_number">
                        </div>
                        <div class="md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-zip">
                                ต้นทุนที่รับมา
                            </label>
                            <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" id="grid-zip" type="number" step="0.01" name="lot_cost">
                        </div>
                        <div class="md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-zip">
                                วันหมดอายุ
                            </label>
                            <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4" type="date" name="lot_expiration">
                        </div>

                    </div>
                    <div class="-mx-3 md:flex mb-2 flex items-center justify-center">
                        <div class="p-3 mt-4  text-center space-x-4 md:block">
                            <button id="closebutton" type="button" class="mb-2 md:mb-0 bg-white px-5 py-2 text-sm shadow-sm font-medium tracking-wider border text-gray-600 rounded-full hover:shadow-lg hover:bg-gray-100">
                                ยกเลิก
                            </button>
                            <!-- ปุ่ม "ลบ" ใน modal -->
                            <button type="submit" name="submit" class="bg-red-500 border border-red-500 px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-white rounded-full hover:shadow-lg hover:bg-red-600">
                                บันทึก
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <script>
            const button = document.getElementById('buttonmodal')
            const closebutton = document.getElementById('closebutton')
            const modal = document.getElementById('modal')

            button.addEventListener('click', () => modal.classList.add('scale-100'))
            closebutton.addEventListener('click', () => modal.classList.remove('scale-100'))
        </script>



        <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
            <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">ชื่อสินค้า</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">วันที่นำเข้าสินค้า</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">จำนวนทั้งหมด</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">ต้นทุนสินค้าที่รับเข้ามา</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">วันหมดอายุสินค้า</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    <?php if (count($lot_data) > 0) : ?>
                        <?php foreach ($lot_data as $row) : ?>
                            <tr class="hover:bg-gray-50">
                                <th class="flex gap-3 px-6 py-4 font-normal text-gray-900">
                                    <div class="relative h-10 w-10">
                                        <img class="h-full w-full rounded-full object-cover object-center" src="<?php echo $row["product_img"]; ?>" />
                                        <span class="absolute right-0 bottom-0 h-2 w-2 rounded-full bg-green-400 ring ring-white"></span>
                                    </div>
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-700"><?php echo $row['product_name']; ?></div>
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-semibold  text-blue-600">
                                            <?php echo $row['lot_date']; ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-semibold  text-blue-600">
                                            <?php echo $row['lot_number']; ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-semibold  text-blue-600">
                                            <?php echo $row['lot_cost']; ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-semibold  text-blue-600">
                                            <?php echo $row['lot_expiration']; ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-4">
                                        <a class="openModal" href="#" data-lot-id="<?php echo $row['id_lot']; ?>" onclick="openDeleteModal(<?php echo $row['id_lot']; ?>)">
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

                                        <a href="edit_lot_product.php?id_lot=<?php echo $row['id_lot']; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6" x-tooltip="tooltip">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                            </svg>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan='6' class="p-3">ไม่พบรายการล็อตสินค้า</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>



    </div>
</body>

  <!-- JavaScript -->
  <script>
        // ฟังก์ชันสำหรับลบสินค้า
        function deleteProduct(lotId) {
            // ทำการลบสินค้าด้วยข้อมูล lotId ที่ได้รับ
            $.ajax({
                type: "POST",
                url: "delete_lot_product.php", // URL หรือ API ที่ใช้ในการลบสินค้า
                data: {
                    lotId: lotId
                }, // ส่งค่า lotId ไปให้กับ API
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
        function openDeleteModal(lotId) {
            var modal = document.getElementById("deleteModal");
            modal.classList.remove("hidden");

            // ส่ง product_id ไปยังปุ่ม "ลบ" ใน Modal เพื่อนำไปใช้ในการลบสินค้า
            var confirmButton = document.getElementById("confirmButton");
            confirmButton.setAttribute("data-lot-id", lotId);
            confirmButton.onclick = function() {
                deleteProduct(lotId);
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

</html>