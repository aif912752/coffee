<?php
// เชื่อมต่อฐานข้อมูล
include('db.php');

// สร้างคำสั่ง SQL เพื่อดึงข้อมูลรายได้และรวมรายได้ตามวัน
$sql = "SELECT DATE(datetime) AS date, SUM(all_money) AS total_income FROM invoice GROUP BY DATE(datetime)";
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
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <section class="container px-4 mx-auto">
        <div class="flex flex-col mt-6 ">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8 shadow-md">
                    <div class="overflow-hidden  dark:border-gray-700 md:rounded-lg border border-gray-200 shadow-md">
                        <table class="min-w-full divide-y rounded-lg border border-gray-200 shadow-md text-gray-500">



                            <thead class="bg-[#E1ECC8]">
                                <tr>
                                    <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-900 dark:text-gray-900">
                                        วันที่
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-900 ">ราคารวม</th>
                                    <th class="bg-[#E1ECC8]"> </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 ">
                                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                                    <tr>


                                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                            <div class="inline px-3 py-1 text-xs font-medium rounded-full text-[#0B2B26] gap-x-2  bg-[#E1ECC8]">
                                                <?= $row['date'] ?>

                                            </div>
                                        </td>
                                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                            <div class="inline px-3 py-1 text-xs font-medium rounded-full text-[#0B2B26] gap-x-2  bg-[#E1ECC8]">
                                                <?= number_format($row['total_income'], 2) ?> บาท
                                            </div>
                                        </td>
                                        <td class="px-4 py-2">
                                            <form method="POST" action="income-content.php">
                                                <input type="hidden" name="selected_date" value="<?= $row['date'] ?>">
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">ดูรายการใบเสร็จทั้งหมด</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>