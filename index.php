<?php
require_once('db.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="card.css">
</head>
<!-- ไอคอน -->
<script src="https://cdn.lordicon.com/bhenfmcm.js"></script>

<!-- tailwindcss -->
<script src="https://cdn.tailwindcss.com"></script>
<!-- ตัว Tab ALL -->
<style>
    /* Set height of body and the document to 100% to enable "full page tabs" */
    body,
    html {
        height: 100%;
        margin: 0;
        font-family: Arial;
    }

    /* Style tab links */
    .tablink {
        background-color: #555;
        color: white;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        font-size: 17px;
        width: 33.3%;
    }

    .tablink:hover {
        background-color: #777;
    }

    /* Style the tab content (and add height:100% for full page content) */
    .tabcontent {
        color: white;
        display: none;
        padding: 100px 20px;
        height: 100%;
    }
</style>

<body>
    <?php
    // นำไฟล์ sidebar เข้ามา
    include 'sidebar.html'; ?>

    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4 ">

        <button class="tablink" onclick="openPage('All', this, 'red')" id="defaultOpen">ทั้งหมด</button>
        <button class="tablink" onclick="openPage('Drink', this, 'green')">เครื่องดื่ม</button>
        <button class="tablink" onclick="openPage('Bakery', this, 'blue')">เบเกอรี่</button>


        <!-- Tab All -->
        <div id="All" class="tabcontent">
            <div class="grid grid-cols-3 gap-4 px-8 py-8">

                <?php
                // Fetch all product data from the "product" table
                include 'db.php';
                $sql = "SELECT * FROM product";
                $stmt = $conn->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $productId = $row['id_product'];
                    $productName = $row['product_name'];
                    $productPrice = $row['product_price'];
                    $productImage = $row['product_img'];
                    $productRemaining = $row['remaining'];
                    // Add other fields if needed
                ?>
                    <!-- The card for the product -->
                    <div class="card">
                        <div class="flex justify-end">
                            <div class="grow-0 rounded-md shadow-sm px-2  bg-yellow-100 text-gray-900">
                                คงเหลือ: <?php echo $productRemaining; ?>
                            </div>
                        </div>
                        <div class="card-img">
                            <img class="img" src="<?php echo $productImage; ?>">
                        </div>
                        <div class="card-title"><?php echo $productName; ?></div>

                        <div class="card-footer">
                            <div class="card-price"><span>$</span><?php echo $productPrice; ?></div>
                            <!-- Add to Cart button with data-id attribute -->
                            <button type="button" class="relative inline-flex items-center px-3 text-sm font-medium text-center bg-yellow-100 hover:bg-yellow-200 text-white focus:ring-4 focus:outline-none focus:ring-yellow-300 border border-black rounded-lg cartButton" data-id="<?php echo $productId; ?>">
                                <lord-icon src="https://cdn.lordicon.com/slkvcfos.json" trigger="hover" colors="primary:#121331,secondary:#08a88a" style="width:50px;height:50px">
                                </lord-icon>
                                <span class="sr-only">Notifications</span>
                                <!-- Show the cart item count -->
                                <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -right-2 dark:border-gray-900 cartItemCount" style="display: none;"></div>
                            </button>
                        </div>
                    </div>
                <?php
                } // End of while loop for products
                ?>
            </div>
        </div>


        <script>
            // รายการสินค้าในตะกร้า
            let cartItems = {};

            // ฟังก์ชันสำหรับเพิ่มสินค้าในตะกร้า
            function addToCart(productId) {
                if (!cartItems[productId]) {
                    cartItems[productId] = 1;
                } else {
                    cartItems[productId]++;
                }
            }

            // ฟังก์ชันสำหรับอัปเดตจำนวนสินค้าที่แสดงในปุ่ม
            function updateCartItemCount() {
                const cartButtons = document.querySelectorAll('.cartButton');
                cartButtons.forEach(button => {
                    const cartItemCount = button.querySelector('.cartItemCount');
                    const productId = button.dataset.id;

                    if (cartItems[productId]) {
                        cartItemCount.innerText = cartItems[productId];
                        cartItemCount.style.display = 'inline-flex';
                    } else {
                        cartItemCount.innerText = '';
                        cartItemCount.style.display = 'none';
                    }
                });
            }

            // การดักจับการคลิกที่ปุ่มในแท็บ All
            const allButtons = document.querySelectorAll('#All .cartButton');
            allButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const productId = button.dataset.id;
                    addToCart(productId);
                    updateCartItemCount();


                });
            });

            // การดักจับการคลิกที่ปุ่มในแท็บ Drink
            const drinkButtons = document.querySelectorAll('#Drink .cartButton');
            drinkButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const productId = button.dataset.id;
                    addToCart(productId);
                    updateCartItemCount();
                    updateAllTabItemCount(); // อัปเดตจำนวนสินค้าในแท็บ All ด้วย



                });
            });
            // การดักจับการคลิกที่ปุ่มในแท็บ Bakery
            const bakeryButtons = document.querySelectorAll('#Bakery .cartButton');
            bakeryButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const productId = button.dataset.id;
                    addToCart(productId);
                    updateCartItemCount();
                });
            });
        </script>

        <div id="Drink" class="tabcontent">
            <div class="grid grid-cols-3 gap-4 px-8 py-8">

                <?php
                // Fetch drink product data along with their type_name from the "product" and "type" tables
                $sql_drink = "SELECT product.*, type.type_name 
                      FROM product
                      INNER JOIN type ON product.id_type = type.id_type
                      WHERE type.type_name = 'เครื่องดื่ม'";
                $stmt_drink = $conn->prepare($sql_drink);
                $stmt_drink->execute();
                while ($row_drink = $stmt_drink->fetch(PDO::FETCH_ASSOC)) {
                    $productId = $row_drink['id_product'];
                    $productName = $row_drink['product_name'];
                    $productPrice = $row_drink['product_price'];
                    $productImage = $row_drink['product_img'];
                    // Add other fields if needed
                ?>
                    <!-- The card for drink product -->
                    <div class="card">

                        <div class="card-img">
                            <img class="img" src="<?php echo $productImage; ?>">
                        </div>
                        <div class="card-title"><?php echo $productName; ?></div>

                        <div class="wrapper bg-orange-300 text-gray-800 w-70 h-12 border-2 border-gray-700 rounded-full flex items-center justify-between shadow-md px-2">
                            <label class="btn w-full h-full rounded-full flex justify-center items-center">
                                <input class="input hidden appearance-none" type="radio" name="btn" value="option1" checked>
                                <span class="span text-gray-700">ร้อน</span>
                            </label>
                            <label class="btn w-full h-full rounded-full flex justify-center items-center">
                                <input class="input hidden appearance-none" type="radio" name="btn" value="option2">
                                <span class="span text-gray-700">เย็น</span>
                            </label>
                            <label class="btn w-full h-full rounded-full flex justify-center items-center">
                                <input class="input hidden appearance-none" type="radio" name="btn" value="option3">
                                <span class="span text-gray-700">ปั่น</span>
                            </label>
                        </div>

                        <div class="card-footer">
                            <div class="card-price"><span>$</span><?php echo $productPrice; ?></div>
                            <!-- Add to Cart button with data-id attribute -->
                            <button type="button" class="relative inline-flex items-center px-3 text-sm font-medium text-center bg-yellow-100 hover:bg-yellow-200 text-white focus:ring-4 focus:outline-none focus:ring-yellow-300 border border-black rounded-lg cartButton" data-id="<?php echo $productId; ?>">
                                <lord-icon src="https://cdn.lordicon.com/slkvcfos.json" trigger="hover" colors="primary:#121331,secondary:#08a88a" style="width:50px;height:50px">
                                </lord-icon>
                                <span class="sr-only">Notifications</span>
                                <!-- Show the cart item count -->
                                <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -right-2 dark:border-gray-900 cartItemCount" style="display: none;"></div>
                            </button>
                        </div>
                    </div>
                <?php
                } // End of while loop for drink products
                ?>
            </div>
        </div>

        <div id="Bakery" class="tabcontent">
            <div class="grid grid-cols-3 gap-4 px-8 py-8">
                <?php
                // Fetch bakery product data along with their type_name from the "product" and "type" tables
                $sql_bakery = "SELECT product.*, type.type_name 
                       FROM product
                       INNER JOIN type ON product.id_type = type.id_type
                       WHERE type.type_name = 'เบเกอรี่'";
                $stmt_bakery = $conn->prepare($sql_bakery);
                $stmt_bakery->execute();
                while ($row_bakery = $stmt_bakery->fetch(PDO::FETCH_ASSOC)) {
                    $productId = $row_bakery['id_product'];
                    $productName = $row_bakery['product_name'];
                    $productPrice = $row_bakery['product_price'];
                    $productImage = $row_bakery['product_img'];
                    // Add other fields if needed
                ?>
                    <!-- The card for bakery product -->
                    <div class="card">
                        <div class="card-img">
                            <img class="img" src="<?php echo $productImage; ?>">
                        </div>
                        <div class="card-title"><?php echo $productName; ?></div>

                        <div class="card-footer">
                            <div class="card-price"><span>$</span><?php echo $productPrice; ?></div>
                            <!-- Add to Cart button with data-id attribute -->
                            <button type="button" class="relative inline-flex items-center px-3 text-sm font-medium text-center bg-yellow-100 hover:bg-yellow-200 text-white focus:ring-4 focus:outline-none focus:ring-yellow-300 border border-black rounded-lg cartButton" data-id="<?php echo $productId; ?>">
                                <lord-icon src="https://cdn.lordicon.com/slkvcfos.json" trigger="hover" colors="primary:#121331,secondary:#08a88a" style="width:50px;height:50px">
                                </lord-icon>
                                <span class="sr-only">Notifications</span>
                                <!-- Show the cart item count -->
                                <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -right-2 dark:border-gray-900 cartItemCount" style="display: none;"></div>
                            </button>
                        </div>
                    </div>
                <?php
                } // End of while loop for bakery products
                ?>
            </div>
        </div>
        
    </div>

    <!-- Tab ALL ข้างบน -->
    <script>
        function openPage(pageName, elmnt, color) {
            // Hide all elements with class="tabcontent" by default */
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Remove the background color of all tablinks/buttons
            tablinks = document.getElementsByClassName("tablink");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].style.backgroundColor = "";
            }

            // Show the specific tab content
            document.getElementById(pageName).style.display = "block";

            // Add the specific color to the button used to open the tab content
            elmnt.style.backgroundColor = color;
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
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

</html>