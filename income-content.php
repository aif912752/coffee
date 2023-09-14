<?php
// เชื่อมต่อฐานข้อมูล
include('db.php');

// ตรวจสอบว่ามีการส่งค่า 'selected_date' มาหรือไม่
if (isset($_POST['selected_date'])) {
    $selectedDate = $_POST['selected_date'];
    $sql = "SELECT i.*, a.username AS username
    FROM invoice i
    JOIN admin a ON i.id_admin = a.id_admin
    WHERE DATE(i.datetime) = :selected_date
    ORDER BY i.id_invoice DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':selected_date', $_POST['selected_date']);
    $stmt->execute();

?>



    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="button-back.css">


    <section class="container px-4 mx-auto">
        <div class="flex flex-col mt-6 ">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8 shadow-md">
                    <div class="overflow-hidden  dark:border-gray-700 md:rounded-lg border border-gray-200 shadow-md">
                        <table class="min-w-full divide-y rounded-lg border border-gray-200 shadow-md text-gray-500">
                            <thead class="bg-[#E1ECC8]">
                                <tr>
                                    <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-900 dark:text-gray-900">
                                        <button class="flex items-center gap-x-3 focus:outline-none">
                                            <span>ID ใบเสร็จ</span>
                                        </button>
                                    </th>
                                    <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-900 dark:text-gray-900">
                                        วันที่
                                    </th>
                                    <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-900 dark:text-gray-900">
                                        พนักงาน
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-900 ">ราคารวม</th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 ">
                                <!-- วนลูปเรียกข้อมูลใบเสร็จทั้งหมดและแสดงในตาราง -->
                                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                            <div>
                                                <p class="text-sm font-normal text-gray-600 dark:text-gray-900"><?= $row['id_invoice'] ?> </p>
                                        </td>

                                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                            <div class="inline px-3 py-1 text-xs font-medium rounded-full text-[#0B2B26] gap-x-2  bg-[#E1ECC8]">
                                                <?= $row['username'] ?>

                                            </div>
                                        </td>
                                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                            <div class="income inline px-3 py-1 text-xs font-medium rounded-full text-[#0B2B26] gap-x-2  bg-[#E1ECC8]">
                                                <?= $row['all_money'] ?>

                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap ">
                                            <div class="flex gap-4">
                                                <a href="view_bill.php?id=<?= $row['id_invoice'] ?>" target="_blank" class="middle none center rounded-lg bg-orange-500 py-3 px-2 font-sans text-xs font-bold uppercase text-white shadow-md
                                    shadow-orange-500/20 transition-all hover:shadow-lg hover:shadow-orange-500/40 focus:opacity-[0.85] focus:shadow-none 
                                    active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true">
                                                    รายละเอียดใบเสร็จ
                                                </a>
                                                <a data-promotion-id="<?php echo $row['id_invoice']; ?>" onclick="openDeleteModal(<?php echo $row['id_invoice']; ?>)" " data-ripple-light=" true" class="openModal middle none center rounded-lg bg-orange-500 py-3 px-2 font-sans text-xs font-bold uppercase 
                                    text-white shadow-md shadow-orange-500/20 transition-all hover:shadow-lg hover:shadow-orange-500/40 focus:opacity-[0.85] 
                                    focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                                                    ลบ
                                                </a>
                                            </div>
                                        </td>


                                    <tr>
                                    <?php endwhile; ?>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>


                    </div>
                    <div class="flex justify-end"> 
                        <button class="button mt-3  justify-end" id="backButton">
                            กลับ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>


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

    <!-- ลบใบเสร็จ -->
    <script>
        // ฟังก์ชันสำหรับลบสินค้า
        function deleteInvoiceId(invoiceId) {
            // ทำการลบสินค้าด้วยข้อมูล promotionId ที่ได้รับ
            $.ajax({
                type: "POST",
                url: "delete_income.php", // URL หรือ API ที่ใช้ในการลบสินค้า
                data: {
                    invoiceId: invoiceId
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
        function openDeleteModal(invoiceId) {
            var modal = document.getElementById("deleteModal");
            modal.classList.remove("hidden");

            // ส่ง product_id ไปยังปุ่ม "ลบ" ใน Modal เพื่อนำไปใช้ในการลบสินค้า
            var confirmButton = document.getElementById("confirmButton");
            confirmButton.setAttribute("data-invoice-id", invoiceId);
            confirmButton.onclick = function() {
                deleteInvoiceId(invoiceId);
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

<script>
    // เรียกใช้งานเมื่อคลิกที่ปุ่ม "กลับ"
    document.getElementById("backButton").addEventListener("click", function() {
        window.location.href = "income.php"; // เปลี่ยน URL เพื่อย้อนกลับไปยังหน้า daily-income-content.php
    });
</script>