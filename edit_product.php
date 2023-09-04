<?php
// Assuming you have a database connection $conn already established
require_once('db.php');

// Fetch product data for pre-filling the form
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_product = $_GET['id'];

    try {
        // Prepare SQL statement to fetch product data
        $stmt = $conn->prepare("SELECT * FROM product WHERE id_product = :id_product");

        // Bind parameter
        $stmt->bindParam(':id_product', $id_product);

        // Execute the statement
        $stmt->execute();

        // Fetch the product data as an associative array
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching product data: " . $e->getMessage();
    }
}
// Process the form data when submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        // Get the product ID from the URL parameter
        $id_product = $_GET['id'];

        // Get data from the form
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $id_type = $_POST['id_type']; // ข้อมูล "ประเภทสินค้า"

        // Check if an image was uploaded
        if (isset($_FILES['product_img']) && $_FILES['product_img']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "product_images/";
            $target_file = $target_dir . basename($_FILES["product_img"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Allow only certain image file formats (you can add more formats if needed)
            if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
            } else {
                // If everything is OK, try to upload the file
                if (move_uploaded_file($_FILES["product_img"]["tmp_name"], $target_file)) {
                    // The file has been uploaded successfully, now update the database

                    // Prepare SQL statement to update product information with the new image path
                    $stmt = $conn->prepare("UPDATE product SET product_name = :product_name, product_price = :product_price, id_type = :id_type, product_img = :product_img WHERE id_product = :id_product");

                    // Bind parameters
                    $stmt->bindParam(':id_product', $id_product);
                    $stmt->bindParam(':product_name', $product_name);
                    $stmt->bindParam(':product_price', $product_price);
                    $stmt->bindParam(':id_type', $id_type);
                    $stmt->bindParam(':product_img', $target_file); // Save the new image path in the database

                    // Execute the update statement
                    if ($stmt->execute()) {
                        // Show Popup
                        echo "<script>
                                 function showPopup() {
                                alert('แก้ไขข้อมูลสำเร็จ');
                                window.location.href = 'table_product.php';
                            }
                                showPopup();
                                </script>";
                    } else {
                        // Show Error Message
                        echo "Error updating product information.";
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            // Prepare SQL statement to update product information without changing the image path
            $stmt = $conn->prepare("UPDATE product SET product_name = :product_name, product_price = :product_price, id_type = :id_type WHERE id_product = :id_product");

            // Bind parameters
            $stmt->bindParam(':id_product', $id_product);
            $stmt->bindParam(':product_name', $product_name);
            $stmt->bindParam(':product_price', $product_price);
            $stmt->bindParam(':id_type', $id_type);

            // Execute the update statement
            if ($stmt->execute()) {
                // Show Popup
                echo "<script>
                function showPopup() {
                    alert('แก้ไขข้อมูลสำเร็จ');
                    window.location.href = 'table_product.php';
                }
                showPopup();
            </script>";
            } else {
                // Show Error Message
                echo "Error updating product information.";
            }
        }
    }
}
?>
<!-- Add the following JavaScript code at the end of your HTML file, before the closing </body> tag -->
<script>
    function showPopup() {
        alert("แก้ไขข้อมูลสำเร็จ");
        window.location.href = 'table_product.php';
    }
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <!-- Add SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">

    <!-- Add SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>


    <script src="https://cdn.tailwindcss.com"></script>

    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">
</head>

<body>

    <!-- component -->
    <main class="flex min-h-screen flex-col justify-center bg-cyan-500 p-16">
        <h1 class="text-3xl font-bold text-white p-4 mb-8">แก้ไขข้อมูลสินค้า</h1>
        <div class="w-full rounded-xl bg-white p-4 shadow-2xl shadow-white/40">
            <!-- Update the form action to submit the data to the same page -->
            <form action="edit_product.php?id=<?php echo $id_product; ?>" method="post" enctype="multipart/form-data">
                <div class="mb-4 grid grid-cols-2 gap-4 my-5 mx-5">
                    <div class="flex flex-col">
                        <label for="product_name" class="mb-2 font-semibold">ชื่อสินค้า</label>
                        <!-- Pre-fill the product_name field with the fetched product data -->
                        <input type="text" id="product_name" name="product_name" class="w-full max-w-lg rounded-lg border border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40" value="<?php echo $product['product_name']; ?>" />
                    </div>
                    <div class="flex flex-col">
                        <label for="product_price" class="mb-2 font-semibold">ราคา</label>
                        <!-- Pre-fill the product_price field with the fetched product data -->
                        <input type="text" id="product_price" name="product_price" class="w-full max-w-[200px] rounded-lg border border-slate-200 px-2 py-1 hover:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500/40 active:ring active:ring-blue-500/40" value="<?php echo $product['product_price']; ?>" />
                    </div>
                </div>
                <div class="mb-4 grid grid-cols-2 gap-4 mx-5 my-5">
                  
                    <div class="mb-4 flex flex-col">
                        <label for="type_name" class="mb-2 font-semibold">ประเภท</label>
                        <div class="flex gap-x-6">
                            <?php
                            // Fetch all type data from the 'type' table
                            try {
                                $stmt = $conn->prepare("SELECT * FROM type");
                                $stmt->execute();
                                $types = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Display radio buttons for each type
                                foreach ($types as $type) {
                                    $type_id = $type['id_type'];
                                    $type_name = $type['type_name'];
                                    $checked = ($product['id_type'] == $type_id) ? 'checked' : '';
                            ?>
                                    <div class="flex">
                                        <input type="radio" name="id_type" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="type-<?php echo $type_id; ?>" value="<?php echo $type_id; ?>" <?php echo $checked; ?>>
                                        <label for="type-<?php echo $type_id; ?>" class="text-sm text-gray-500 ml-2 dark:text-gray-400"><?php echo $type_name; ?></label>
                                    </div>
                            <?php
                                }
                            } catch (PDOException $e) {
                                // Handle the error if type data retrieval fails
                                die("Error fetching type data: " . $e->getMessage());
                            }
                            ?>
                        </div>
                    </div>
                    <!-- component -->


                </div>
                <label for="dropzone-file" class="mx-auto cursor-pointer flex w-full max-w-lg flex-col items-center rounded-xl border-2 border-dashed border-blue-400 bg-white p-6 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <h2 class="mt-4 text-xl font-medium text-gray-700 tracking-wide">เปลี่ยนรูปภาพ</h2>
                    <?php if ($product['product_img']) { ?>
                        <!-- If an image is available, display the new uploaded image -->
                        <img id="uploaded-image" src="<?php echo $product['product_img']; ?>" class="w-full max-w-[200px] rounded-lg" />
                    <?php } else { ?>
                        <!-- If no image is available, display a message -->
                        <p>No image uploaded for this product.</p>
                    <?php } ?>
                    <p class="mt-2 text-gray-500 tracking-wide">Upload or darg & drop your file SVG, PNG, JPG or GIF. </p>
                    <input id="dropzone-file" type="file" name="product_img" class="hidden" />
                </label>
                <!-- Add a submit button to send the updated data to the server -->
                <div class="text-center min-h-[140px] w-full place-items-center overflow-x-scroll rounded-lg p-6 lg:overflow-visible">
                    <button type="submit" name="submit" class="middle none center mr-4 rounded-lg bg-green-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-green-500/20 transition-all hover:shadow-lg hover:shadow-green-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true">
                        บันทึก
                    </button>
                    <!-- Add a cancel link or button to go back to the product_list.php page -->
                    <a href="table_product.php" class="middle none center mr-4 rounded-lg bg-red-500 py-3 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-red-500/20 transition-all hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-light="true">
                        ยกเลิก
                    </a>
                </div>
            </form>
        </div>
    </main>
    <!-- ส่วนของ JavaScript -->

    <script>
        // Function to display the selected image
        function displayImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    // Display the selected image
                    document.getElementById('uploaded-image').setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Event listener to trigger the displayImage function when a new image is selected
        document.getElementById('dropzone-file').addEventListener('change', function() {
            displayImage(this);
        });
    </script>
</body>

</html>