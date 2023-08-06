<!DOCTYPE html>
<!-- Coding By CodingNepal - codingnepalweb.com -->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>

    <!-- Boxiocns CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link rel="stylesheet" href="css/checkbox.css">

</head>

<body>
    <?php
    // นำไฟล์ sidebar เข้ามา
    include 'sidebar.html';
    ?>
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4 ">

        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mt-4 ml-4" onclick="openModal()">เพิ่มประเภทสินค้า</button>

        <!-- The Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <!-- Close button -->
                <span class="close" onclick="closeModal()">&times;</span>

                <input type="text" id="productTypeInput" class="py-3 px-4 block w-full border border-gray-300 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700  dark:text-slate-900" placeholder="กรอกประเภทสินค้า">

                <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mt-4" onclick="addProductType()">เพิ่มประเภทสินค้า</button>
            </div>
        </div>


        <div class="container ml-3 p-10 rounded shadow-md mt-4">
            <header class="text-2xl font-bold mb-6">เพิ่มสินค้า</header>
            <form action="add_product.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block font-bold mb-2">ประเภท</label>
                    <?php
                    require_once('db.php');

                    // Retrieve product types from the "type" table
                    $sql = "SELECT * FROM type";
                    $stmt = $conn->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $typeId = $row['id_type'];
                        $typeName = $row['type_name'];
                    ?>
                        <label class="inline-flex items-center mr-4">
                            <input type="radio" name="product_type" value="<?php echo $typeId; ?>" required>
                            <span class="ml-2"><?php echo $typeName; ?></span>
                        </label>
                    <?php } ?>
                </div>


                <div class="mb-4">
                    <label for="product_name" class="block font-bold mb-2">ชื่อสินค้า</label>
                    <input type="text" name="product_name" id="product_name" class="w-full px-3 py-2 border rounded focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label for="product_price" class="block font-bold mb-2">ราคา</label>
                    <input type="number" name="product_price" id="product_price" class="w-full px-3 py-2 border rounded focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label for="product_img" class="block font-bold mb-2">รูปภาพ</label>
                    <input type="file" name="file-input" id="file-input" class="block w-full border border-gray-200 shadow-sm rounded-md text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:text-gray-400
    file:bg-transparent file:border-0 file:bg-gray-100 file:mr-4 file:py-3 file:px-4 dark:file:bg-gray-700 dark:file:text-gray-400">
                </div>

                <div class="mb-4">
                    <label for="remaining" class="block font-bold mb-2">จำนวนคงเหลือ</label>
                    <input type="number" name="remaining" id="remaining" class="w-full px-3 py-2 border rounded focus:outline-none focus:shadow-outline" required>
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">เพิ่มสินค้า</button>
            </form>
        </div>
    </div>
    <!-- Modal -->
    <style>
        /* ... CSS styles for the modal ... */
        /* กำหนดความกว้างของโมดอลและจัดให้ตรงกลาง */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fefefe;
            /* กำหนดความกว้างของเนื้อหาในโมดอล */
            width: 30%;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Close Button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

    <!-- ปุ้มเด้งเพิ่มประเภทสินค้า -->
    <script>
        // Function to open the modal
        function openModal() {
            document.getElementById("myModal").style.display = "flex";
            // เก็บสถานะของโมดอลลงใน sessionStorage
            sessionStorage.setItem("modalOpen", "true");
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById("myModal").style.display = "none";
            // เก็บสถานะของโมดอลลงใน sessionStorage
            sessionStorage.setItem("modalOpen", "false");
        }

        // เมื่อคลิกที่พื้นที่นอกของ Modal ให้ปิดโมดอล
        window.onclick = function(event) {
            var modal = document.getElementById("myModal");
            if (event.target === modal) {
                closeModal();
            }
        }
        //ส่งค่าไป save_type.php
        function addProductType() {
            // Get the input value
            const productType = document.getElementById("productTypeInput").value;

            // Create a new XMLHttpRequest object
            const xhr = new XMLHttpRequest();

            // Configure the request
            xhr.open('POST', 'save_type.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            // Define the callback function
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    // Handle the response from the server
                    if (xhr.responseText === "Success") {
                        alert("เพิ่มประเภทสำเร็จ"); // Show success message
                        closeModal(); // Close the modal after saving data
                    } else {
                        alert("เกิดข้อผิดพลาดในการบันทึกข้อมูล");
                    }
                }
            };

            // Send the data to the server
            xhr.send('type_name=' + encodeURIComponent(productType));
        }

        // เมื่อโหลดหน้าเว็บใหม่ ตรวจสอบสถานะของโมดอลใน sessionStorage
        window.onload = function() {
            var modalOpen = sessionStorage.getItem("modalOpen");
            if (modalOpen === "true") {
                // ถ้าสถานะเป็น true ให้เปิดโมดอลอัตโนมัติ
                document.getElementById("myModal").style.display = "flex";
            } else {
                // ถ้าสถานะเป็น false ให้ปิดโมดอลอัตโนมัติ
                document.getElementById("myModal").style.display = "none";
            }
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

</body>