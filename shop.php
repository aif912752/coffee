<?php
session_start(); // ต้องเรียก session_start() ทุกครั้งที่ต้องการเข้าถึงตัวแปร $_SESSION
require_once('db.php');

if (isset($_SESSION['username'])) {
    $_SESSION['username'];
    // ทำสิ่งอื่นๆ หลังจากแสดง username เช่น แสดงสินค้า ปุ่มออกจากระบบ เป็นต้น
} else {
    // ถ้าไม่มีการเข้าสู่ระบบ ให้ทำสิ่งที่คุณต้องการ ยกตัวอย่างเช่นแสดงแบบฟอร์มล็อกอิน
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="card.css">
    <link rel="stylesheet" href="side.css">

</head>
<!-- ไอคอน -->
<script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

<script src="product_alert.js"></script>

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
        background-color: #fff;
        color: white;
        --main-color: #323232;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        font-size: 17px;
        width: 33.3%;
        border: 2px solid var(--main-color);
        box-shadow: 4px 4px var(--main-color);
    }

    .tablink:hover {
        background-color: #FFFF00;
    }

    /* Style the tab content (and add height:100% for full page content) */
    .tabcontent {
        color: white;
        display: none;
        height: 100%;
    }
</style>

<body>

    <?php
    // นำไฟล์ sidebar เข้ามา
    include 'sidebar.html'; ?>

    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4 ">

        <button class="tablink text-black" onclick="openPage('All', this, '#7CFC00')" id="defaultOpen">ทั้งหมด</button>
        <button class="tablink text-black" onclick="openPage('Drink', this, '#7CFC00')">เครื่องดื่ม</button>
        <button class="tablink text-black" onclick="openPage('Bakery', this, '#7CFC00')">เบเกอรี่</button>



        <div id="expiration-alert" class="bg-yellow-200 text-yellow-800 p-4 rounded-md shadow-lg mb-4" style="display: none;">
    <p class="font-semibold">แจ้งเตือน: สินค้าใกล้หมดอายุ</p>
    <p id="expiration-message"></p> <!-- ข้อความจะถูกแทนที่โดย JavaScript -->
</div>


        <!-- ปุ่มคำนวณรวมยอดเงินทั้งหมด -->
        <div class="flex justify-end px-8 py-4">
            <button type="button" class="calculateButton  bg-yellow-200 relative text-black px-4 py-2 rounded-lg shadow-sm hover:bg-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                <lord-icon src="https://cdn.lordicon.com/slkvcfos.json" trigger="hover" colors="primary:#121331,secondary:#08a88a" style="width:50px;height:50px"></lord-icon>
                <div class="cartItemCount absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -right-2 dark:border-gray-900 "></div>
            </button>
        </div>

        <!-- Modal ปุ่มคำนวณรวมยอดเงินทั้งหมด -->
        <div class="hidden " id="cartModal">
            <div class="fixed left-0 top-0 h-full w-full items-center justify-center bg-white bg-opacity-50 py-10 z-40">
                <div class="flex justify-center items-end text-center min-h-screen sm:block">
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">​</span>
                    <div class="inline-block text-left bg-gradient-to-r from-rose-100 to-teal-100 rounded-lg overflow-hidden align-bottom transition-all transform
                                shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full ">
                        <div class="items-center w-full mr-auto ml-auto relative max-w-7xl md:px-12 lg:px-14">
                            <div class="mt-4 mr-auto mb-4 ml-auto bg-whi max-w-lg">
                                <h1 class="py-6 border-b-2 text-xl text-gray-600 px-8">รายการสินค้าในตะกร้า</h1>

                                <ul class="py-6 border-b space-y-6 px-8">
                                    <div id="cartItems" class="space-y-2"></div>
                                </ul>

                                <div class="px-8 border-b">
                                    <div id="promotionProducts" class="space-y-2"></div>
                                </div>

                                <div class="font-semibold text-xl px-8 flex justify-between py-8 text-gray-600">
                                    <span id="totalPrice" class="mt-4">ราคารวมทั้งหมด: $0.00</span>

                                </div>

                                <div class="flex justify-center  ">
                                    <button type="button" id="cancelButton" class="mt-4 mr-4 px-4 py-2 bg-red-300 hover:bg-red-400 rounded-lg focus:outline-none">ยกเลิก</button>
                                    <button type="button" id="closeModal" class="mt-4 mr-4 px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg focus:outline-none">ปิด</button>
                                    <button type="button" id="orderButton" class="mt-4 px-4 py-2 flex-initial w-32 bg-green-300 hover:bg-gray-400 rounded-lg focus:outline-none">สั่งซื้อ</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- script แสดง Modal -->
        <script>
            const calculateButton = document.querySelector('.calculateButton');
            const cartModal = document.getElementById('cartModal');
            const closeModal = document.getElementById('closeModal');
            const cartItemsDiv = document.getElementById('cartItems');
            const totalPriceDiv = document.getElementById('totalPrice');
            const promotionProductsDiv = document.getElementById('promotionProducts');
            const orderButton = document.getElementById('orderButton');
            const cancelButton = document.getElementById('cancelButton');

            let promotionDiscount = 0;
            let total = 0;
            let selectedPromotionId = []; // สร้างตัวแปร selectedPromotionId และกำหนดค่าเริ่มต้นเป็น null

            calculateButton.addEventListener('click', () => {
                updateCartModal();
                cartModal.classList.remove('hidden');
            });

            closeModal.addEventListener('click', () => {
                cartModal.classList.add('hidden');
            });

            window.addEventListener('click', event => {
                if (event.target === cartModal) {
                    cartModal.classList.add('hidden');
                }
            });

            function updateCartItemQuantity(btn, act) {
                const productId = btn.dataset.id;
                const index = cartItems.findIndex(p => p.id == productId);
                const qty = cartItems[index].qty;
                if (act == 'plus') {
                    cartItems[index].qty++;
                }
                if (act == 'minus' && qty >= 1) {
                    cartItems[index].qty--;
                }
                updateCartModal();
            }

            function updateCartModal() {
                cartItemsDiv.innerHTML = '';
                cartItems.forEach(p => {
                    const productId = parseFloat(p.id);
                    const productPrice = parseFloat(p.price);
                    const productName = p.product_name;
                    const quantity = p.qty;
                    const itemTotalPrice = productPrice * quantity;
                    totalPrice += itemTotalPrice;
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'cart-item';
                    itemDiv.innerHTML = `
                <li class="grid grid-cols-6 gap-2 border-b-1">
                    <div class="col-span-1 self-center">
                    </div>
                    <div class="flex flex-col col-span-2 pt-2">
                        <span class="cart-item-name text-gray-600 text-md font-semi-bold">${productName}</span>
                    </div>
                    <div class="col-span-2 pt-3">
                        <div class="flex items-center text-sm justify-between">
                            <button data-id="${productId}" class="decreaseQuantity px-2 bg-gray-300 hover:bg-gray-400 rounded focus:outline-none">-</button>
                            <input type="number" class="quantityInput text-center w-12 " value="${quantity}">
                            <button data-id="${productId}" class="increaseQuantity mr-2 px-2 bg-gray-300 hover:bg-gray-400 rounded focus:outline-none">+</button>
                            <span class="cart-item-price ml-6 text-pink-400 font-semibold inline-block">$${itemTotalPrice.toFixed(2)}</span>
                        </div>
                    </div>
                </li>`;
                    cartItemsDiv.appendChild(itemDiv);
                    const increaseButton = itemDiv.querySelector('.increaseQuantity');
                    const decreaseButton = itemDiv.querySelector('.decreaseQuantity');
                    const quantityInput = itemDiv.querySelector('.quantityInput');
                    increaseButton.addEventListener('click', (event) => {
                        updateCartItemQuantity(event.target, 'plus');
                    });
                    decreaseButton.addEventListener('click', (event) => {
                        updateCartItemQuantity(event.target, 'minus');
                    });
                });

                totalPriceDiv.textContent = `$${(totalPrice - promotionDiscount).toFixed(2)}`;

                fetch('get_promotions.php')
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                       
                        const participatingProductName = cartItems.map(item => item.product_name);
                        let promotionsEl = ``;
                        data.forEach(promotion => {
                            
                            const promotionName = promotion.promotion_name;
                            const promotionType = promotion.discount_type;
                            const promotionAmount = parseFloat(promotion.discount_amount);
                            const hasParticipatingProducts = promotion.participating_products.some(item => participatingProductName.includes(item));
                            if (hasParticipatingProducts) {
                                selectedPromotionId.push(promotion.id_promotion)
                                promotionsEl += `
                        <div class="flex justify-between py-4 text-gray-600">
                            <span> ${promotionName}</span>`;
                                if (promotionType === 'percentage') {
                                    promotionsEl += ` 
                                <span class="font-semibold text-pink-500">- ส่วนลด ${promotionAmount}%</span>
                            </div>`;
                                } else if (promotionType === 'amount') {
                                    promotionsEl += ` <span class="font-semibold text-pink-500">
                                - ส่วนลด $${promotionAmount.toFixed(2)} </span> </div>`;
                                }
                                promotionsEl += `</p>`;
          // กำหนดค่า selectedPromotionId ให้เป็น ID ของโปรโมชั่นที่มีสินค้าที่ร่วมรายการ
                            }
                        });

                        promotionDiscount = calculatePromotionDiscount(data);
                        console.log('is', promotionDiscount);
                        promotionProductsDiv.innerHTML = promotionsEl;
                        const totalPrice = currentTotal();
                        total = totalPrice - promotionDiscount;
                        const totalAmount = totalPrice - promotionDiscount;
                        totalPriceDiv.textContent = `ราคารวมทั้งหมด: $${totalAmount.toFixed(2)}`;
                    })
                    .catch(error => console.error('Error fetching promotions:', error));
            }

            function currentTotal() {
                return cartItems
                    .map((p, i) => parseInt(p.qty) * parseFloat(p.price))
                    .reduce((prev, current) => prev + current, 0);
            }

            function calculatePromotionDiscount(promotions) {
                const participatingProductNames = cartItems.map(p => p.product_name);
                let totalDiscount = 0;

                promotions.forEach(promotion => {
                    const promotionType = promotion.discount_type;
                    const promotionAmount = parseFloat(promotion.discount_amount);
                    let discountedAmount = 0;

                    promotion.participating_products.forEach((n) => {
                        const hasResult = participatingProductNames.indexOf(n);

                        if (hasResult >= 0) {
                            if (promotionType === 'percentage') {
                                const totalProductPrice = cartItems
                                    .filter((v, index) => participatingProductNames[index] === n)
                                    .map((p, i) => parseInt(p.qty) * parseFloat(p.price))
                                    .reduce((prev, current) => prev + current, 0);
                                discountedAmount += (totalProductPrice * (promotionAmount / 100));
                            } else if (promotionType === 'amount') {
                                discountedAmount += promotionAmount;
                            }
                        }
                    });

                    totalDiscount += discountedAmount;
                });

                return totalDiscount;
            }

            orderButton.addEventListener('click', () => {
                const orderData = {
                    cartItems: cartItems,
                    totalPrice: total,
                    id_promotion: selectedPromotionId
                };
                console.log(orderData);
                fetch('save_order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(orderData)
                    })
                    .then(response => response.json()) // แก้ไขนี้ให้รับข้อมูลเป็น JSON
                    .then(data => {
                        if (data.success && data.invoice_id) { // ตรวจสอบค่า success ใน JSON
                            alert('สั่งซื้อสำเร็จแล้ว!');
                            // ดึง ID ของใบเสร็จจากข้อมูลที่ส่งกลับหลังจากการสั่งซื้อ
                            const invoiceId = data.invoice_id;

                            // เช็คให้แน่ใจว่ามีค่า invoiceId และไม่เป็น undefined หรือ null
                            if (invoiceId) {
                                // เด้งไปยังหน้ารายละเอียดการสั่งซื้อ
                                window.open('order_details.php?invoice_id=' + invoiceId, '_blank');
                                location.reload();
                            } else {
                                console.error('ไม่พบ invoice_id หรือค่าไม่ถูกต้อง');
                            }
                        } else {
                            console.error('การสั่งซื้อไม่สำเร็จ');
                        }
                    })
                    .catch(error => console.error('Error saving order:', error));
            });

            // เพิ่ม event listener ในการคลิกปุ่ม "ยกเลิก"
            cancelButton.addEventListener('click', () => {
                cartModal.classList.add('hidden');
                window.location.reload();
            });
        </script>




        <!-- Tab All -->
        <div id="All" class="tabcontent">
            <div class="grid grid-cols-3 gap-4 px-8 py-8">
                <?php
                require_once('add_lot.php'); // เรียกใช้ฟังก์ชัน calculateRemainingQuantity ที่มีอยู่ในไฟล์ add_lot.php
                require_once('db.php');
                $sql = "SELECT * FROM product";
                $stmt = $conn->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $productId = $row['id_product'];
                    $productName = $row['product_name'];
                    $productPrice = $row['product_price'];
                    $productImage = $row['product_img'];

                    // ดึงจำนวนคงเหลือ (lot_number) จากตาราง lot
                    $sql_lot = "SELECT * FROM lot WHERE id_product = :id_product ";
                    //

                    $stmt_lot = $conn->prepare($sql_lot);
                    $stmt_lot->bindParam(':id_product', $productId);
                    $stmt_lot->execute();

                    $productRemaining = 0;
                    if ($stmt_lot->rowCount() > 0) {
                        while ($row_lot = $stmt_lot->fetch(PDO::FETCH_ASSOC)) {
                            $productRemaining += $row_lot['lot_number'];
                        }
                    }

                    // คำนวณจำนวนคงเหลือใหม่โดยใช้ฟังก์ชัน calculateRemainingQuantity
                    // $productRemaining = calculateRemainingQuantity($conn, $productId);
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
                            <button type="button" class="cartButton relative inline-flex items-center px-3 text-sm font-medium text-center bg-yellow-100 hover:bg-yellow-200 text-white focus:ring-4 focus:outline-none focus:ring-yellow-300 border border-black rounded-lg " data-id="<?php echo $productId; ?>" data-name="<?php echo $productName; ?>" data-price="<?php echo $productPrice; ?>">
                                <lord-icon src="https://cdn.lordicon.com/slkvcfos.json" trigger="hover" colors="primary:#121331,secondary:#08a88a" style="width:50px;height:50px"></lord-icon>
                            </button>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

        <div id="Drink" class="tabcontent">
            <div class="grid grid-cols-3 gap-4 px-8 py-8">
                <?php
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
                    // ดึงจำนวนคงเหลือ (lot_number) จากตาราง lot
                    $sql_lot = "SELECT SUM(lot_number) AS total_lot_number FROM lot WHERE id_product = :id_product GROUP BY id_product";
                    $stmt_lot = $conn->prepare($sql_lot);
                    $stmt_lot->bindParam(':id_product', $productId);
                    $stmt_lot->execute();
                    $row_lot = $stmt_lot->fetch(PDO::FETCH_ASSOC);
                    $productRemaining = $row_lot['total_lot_number'];
                    // คำนวณจำนวนคงเหลือใหม่โดยใช้ฟังก์ชัน calculateRemainingQuantity
                    $productRemaining = calculateRemainingQuantity($conn, $productId);

                    // Add other fields if needed
                ?>
                    <!-- The card for drink product -->
                    <div class="card">
                        <div class="flex justify-end">
                            <div class="grow-0 rounded-md shadow-sm px-2  bg-yellow-100 text-gray-900">
                                คงเหลือ: <span id="drinkRemaining-<?php echo $productId; ?>"><?php echo $productRemaining; ?></span>
                            </div>
                        </div>
                        <div class="card-img">
                            <img class="img" src="<?php echo $productImage; ?>">
                        </div>
                        <div class="card-title"><?php echo $productName; ?></div>
                        <div class="card-footer">
                            <div class="card-price"><span>$</span><?php echo $productPrice; ?></div>
                            <!-- Add to Cart button with data-id attribute -->
                            <button type="button" class="cartButton relative inline-flex items-center px-3 text-sm font-medium text-center bg-yellow-100 hover:bg-yellow-200 text-white focus:ring-4 focus:outline-none focus:ring-yellow-300 border border-black rounded-lg " data-id="<?php echo $productId; ?>" data-name="<?php echo $productName; ?>" data-price="<?php echo $productPrice; ?>">
                                <lord-icon src="https://cdn.lordicon.com/slkvcfos.json" trigger="hover" colors="primary:#121331,secondary:#08a88a" style="width:50px;height:50px"></lord-icon>
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
                    // ดึงจำนวนคงเหลือ (lot_number) จากตาราง lot
                    $sql_lot = "SELECT lot_number FROM lot WHERE id_product = :id_product";
                    $stmt_lot = $conn->prepare($sql_lot);
                    $stmt_lot->bindParam(':id_product', $productId);
                    $stmt_lot->execute();
                    $productRemaining = 0;

                    if ($stmt_lot->rowCount() > 0) {
                        while ($row_lot = $stmt_lot->fetch(PDO::FETCH_ASSOC)) {
                            $productRemaining += $row_lot['lot_number'];
                        }
                    }

                    // คำนวณจำนวนคงเหลือใหม่โดยใช้ฟังก์ชัน calculateRemainingQuantity


                ?>
                    <!-- The card for bakery product -->
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
                            <button type="button" class="cartButton relative inline-flex items-center px-3 text-sm font-medium text-center bg-yellow-100 hover:bg-yellow-200 text-white focus:ring-4 focus:outline-none focus:ring-yellow-300 border border-black rounded-lg " data-id="<?php echo $productId; ?>" data-name="<?php echo $productName; ?>" data-price="<?php echo $productPrice; ?>">
                                <lord-icon src="https://cdn.lordicon.com/slkvcfos.json" trigger="hover" colors="primary:#121331,secondary:#08a88a" style="width:50px;height:50px"></lord-icon>
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

    <!-- เพิ่มสินค้าลงตะกร้า -->
    <script>
        // Add Event Listeners for All tab buttons
        const allButtons = document.querySelectorAll('.cartButton');
        allButtons.forEach(button => {
            const productId = button.dataset.id;

            button.addEventListener('click', () => {
                addToCart(button);
            });
        });
        let cartItems = [];

        function addToCart(product) {
            const productId = product.dataset.id;
            const productName = product.dataset.name;
            const productPrice = product.dataset.price;

            // หาค่าคงเหลือจาก div ที่มี class "grow-0"
            const remainingElement = product.closest('.card').querySelector('.grow-0');
            const remaining = parseInt(remainingElement.textContent.trim().split(' ')[1]); // แยกตัวเลขจากข้อความ "คงเหลือ: X"

            if (remaining > 0) {
                const productIndex = cartItems.findIndex((items) => items.id == productId);

                if (productIndex < 0) {
                    cartItems.push({
                        'id': productId,
                        'qty': 1,
                        'product_name': productName,
                        'price': productPrice
                    });
                } else if (productIndex >= 0) {
                    cartItems[productIndex].qty++;
                }
                updateCartItemCount();
            } else {
                // จำนวนคงเหลือเป็น 0, ไม่สามารถเพิ่มสินค้าลงในตะกร้าได้
                alert('สินค้าหมดแล้ว');
            }
        }

        function updateCartItemCount() {
            const cartButtons = document.querySelectorAll('.cartButton');
            const cartItemCount = document.querySelector('.calculateButton .cartItemCount');
            const cartQty = cartItems.map((item) => item.qty)
            const totalCartItemCount = cartQty.reduce((prev, current) => prev + current)
            if (totalCartItemCount > 0) {
                cartItemCount.innerText = totalCartItemCount;
                cartItemCount.style.display = 'inline-flex';
            } else {
                cartItemCount.innerText = '';
                cartItemCount.style.display = 'none';
            }
        }



        // Add Event Listeners for Drink tab buttons
        // const drinkButtons = document.querySelectorAll('#Drink .cartButton');
        // drinkButtons.forEach(button => {
        //     const productId = button.dataset.id;

        //     button.addEventListener('click', () => {
        //         addToCart(productId);
        //     });
        // });
    </script>

</body>

</html>