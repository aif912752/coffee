<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <?php
    // นำไฟล์ sidebar เข้ามา
    include 'sidebar.html';
    ?>
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4 ">

        <!-- component -->
        <div class="w-auto mx-auto mt-4  rounded justify-center">
            <!-- Tabs -->
            <ul id="tabs" class="inline-flex w-full px-1 pt-2 justify-center">
                <li class="px-4 py-2 -mb-px font-semibold text-gray-800 border-b-2 border-blue-400 rounded-t opacity-50"><a id="default-tab" href="#first">รายได้วันนี้</a></li>
                <li class="px-4 py-2 font-semibold text-gray-800 rounded-t opacity-50"><a href="#second">รายได้ประจำวัน</a></li>
               
            </ul>

            <!-- Tab Contents -->
            <div id="tab-contents">
                <div id="first" class="p-4">
                <?php include('income-day.php'); ?>
                </div>
                <div id="second" class="hidden p-4">
                <?php include('daily-income-content.php'); ?>
                </div>
            
            </div>
        </div>

        <script>
    let tabsContainer = document.querySelector("#tabs");
    let tabTogglers = tabsContainer.querySelectorAll("a");
    console.log(tabTogglers);

    tabTogglers.forEach(function(toggler) {
        toggler.addEventListener("click", function(e) {
            e.preventDefault();

            let tabName = this.getAttribute("href");
            let tabContents = document.querySelector("#tab-contents");

            for (let i = 0; i < tabContents.children.length; i++) {
                tabTogglers[i].parentElement.classList.remove("border-blue-400", "border-b", "-mb-px", "opacity-100");
                tabContents.children[i].classList.remove("hidden");
                if ("#" + tabContents.children[i].id === tabName) {
                    continue;
                }
                tabContents.children[i].classList.add("hidden");
            }
            e.target.parentElement.classList.add("border-blue-400", "border-b-4", "-mb-px", "opacity-100");

            // เรียกฟังก์ชันเพื่อโหลดเนื้อหาจากไฟล์ภายนอกและแทนที่เนื้อหาของแท็บ
            loadContentFromExternalFile(tabName.substring(1));
        });
    });

    // สร้างฟังก์ชันเพื่อโหลดเนื้อหาจากไฟล์ภายนอกและแทนที่เนื้อหาของแท็บ
    function loadContentFromExternalFile(tabName) {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                let tabContentElement = document.querySelector(tabName);
                if (tabContentElement) {
                    tabContentElement.innerHTML = this.responseText;
                }
            }
        };
        xhttp.open("GET", tabName + ".html", true); // เปลี่ยนเส้นทางไฟล์ตามที่คุณเก็บไฟล์ภายนอก
        xhttp.send();
    }

    document.getElementById("default-tab").click(); // เรียกใช้งานแท็บเริ่มต้น
</script>



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