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
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <?php
    // นำไฟล์ sidebar เข้ามา
    include 'sidebar.html';
    ?>
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4 ">
        <section class="container px-4 mx-auto">

            <div class="flex flex-col mt-6 ">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8 shadow-md">
                        <div class="overflow-hidden  dark:border-gray-700 md:rounded-lg border border-gray-200 shadow-md">
                            <table class="min-w-full divide-y rounded-lg border border-gray-200 shadow-md text-gray-500">
                                <thead class="bg-[#E1ECC8]">
                                    <tr class=>
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
                                        <th class="bg-[#E1ECC8]"> </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 ">
                                    <!-- วนลูปเรียกข้อมูลใบเสร็จทั้งหมดและแสดงในตาราง -->
                                    <?php if ($stmt->rowCount() > 0) { ?>
                                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                                            <tr>
                                                <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                                    <div>
                                                        <p class="text-sm font-normal text-gray-600 dark:text-gray-900"><?= $row['id_invoice'] ?>
                                                </td>
                                                </p>
                        </div>
                        </td>
                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                            <div class="inline px-3 py-1 text-xs font-medium rounded-full text-[#0B2B26] gap-x-2 bg-[#E1ECC8]">
                                <?= $row['datetime'] ?>

                            </div>
                        </td>
                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                            <div class="inline px-3 py-1 text-xs font-medium rounded-full text-[#0B2B26] gap-x-2  bg-[#E1ECC8]">
                                <?= $row['username'] ?>

                            </div>
                        </td>
                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                            <div class="inline px-3 py-1 text-xs font-medium rounded-full text-[#0B2B26] gap-x-2  bg-[#E1ECC8]">
                                <?= $row['all_money'] ?>

                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm whitespace-nowrap ">
                            <div class="flex gap-4">
                            <a href="view_bill.php?id=<?= $row['id_invoice'] ?>"  target="_blank" class="middle none center rounded-lg bg-orange-500 py-3 px-2 font-sans text-xs font-bold uppercase text-white shadow-md shadow-orange-500/20 transition-all hover:shadow-lg hover:shadow-orange-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true">
                                    รายละเอียดใบเสร็จ
                                        </a>
                       

                            </div>
                        </td>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap" colspan="4">ไม่พบข้อมูลใบเสร็จประจำวัน</td>
                    </tr>
                <?php } ?>
                </tbody>
                </table>
                    </div>
                </div>
            </div>
    </div>

    </section>
    </div>

</body>
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