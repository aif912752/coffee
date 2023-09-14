<!-- component -->
<!--
Author: Mostafa Ahangarha
License: MIT
Version: v1.1
-->



<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<script src="https://cdn.tailwindcss.com"></script>

	<!--actual component start-->
	<div x-data="setup()">
		<ul class="flex justify-center items-center my-4">
			<template x-for="(tab, index) in tabs" :key="index">
				<li class="cursor-pointer py-2 px-4 text-gray-500 border-b-8"
					:class="activeTab===index ? 'text-green-500 border-green-500' : ''" @click="activeTab = index"
					x-text="tab"></li>
			</template>
		</ul>

		<div class="w-full bg-white p-16 text-center mx-auto border">
			<div x-show="activeTab===0"></div>
			<div x-show="activeTab===1">Content 2</div>
			<div x-show="activeTab===2">Content 3</div>
		</div>
</div>

<script>
	function setup() {
    return {
      activeTab: 0,
      tabs: [
          "Tab No.1",
          "Tab No.2",
          "Tab No.3",
          "Tab No.4",
      ]
    };
  };
</script>
