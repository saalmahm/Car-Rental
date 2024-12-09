<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>car rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body >
    <header class="flex justify-between p-4">
        <a href="/index.php" id="cars">
            <img src="/images/cars.gif" alt="">
        </a>
        <div class="lg:hidden" id="burger-icon">
            <img src="/images/menu.png" alt="Menu">
        </div>
        <div id="sidebar"
            class="shadow-xl fixed top-0 right-0 w-1/3 h-full bg-gray-200  z-50 transform translate-x-full duration-300 ">
            <div class="flex justify-end p-4">
                <button id="close-sidebar" class=" text-3xl">×</button>
            </div>
            <div class="flex flex-col items-center space-y-4 text-white">
                <a href="/pages/voitures.php" class="text-black text-lg">Cars</a>
                <a href="/pages/clients.php" class="text-black text-lg">Customers</a>

            </div>
        </div>
        <div class="hidden lg:flex justify-center space-x-4">
            <ul class="flex items-center text-sm font-medium text-gray-400 mb-0 ">
                <li>
                    <a href="/pages/voitures.php" class="hover:underline me-4 md:me-6">Cars</a>
                </li>
                <li>
                    <a href="/pages/clients.php" class="hover:underline me-4 md:me-6">Customers</a>
                </li>
                <li>
                </li>
            </ul>
        </div>
    </header>
    <script>
        const menu = document.getElementById("burger-icon");
        const sidebar = document.getElementById("sidebar");
        const closeSidebar = document.getElementById("close-sidebar");

        menu.addEventListener("click", () => {
            sidebar.classList.remove("translate-x-full");  
            sidebar.classList.add("translate-x-0");
        });

        closeSidebar.addEventListener("click", () => {
            sidebar.classList.add("translate-x-full");   
            sidebar.classList.remove("translate-x-0");    
        });
    </script>

<section class="bg-blue-200 py-8 relative">
    <div class="px-6 flex items-center justify-between">
        <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 mb-0">List of Contras</h1>
        <button class="bg-blue-600 text-white py-3 px-3 rounded-full font-semibold text-lg hover:bg-blue-700 transition-colors duration-300">
            Add new Contras
        </button>
    </div>
</section>



<div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-2 my-8">
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-50 bg-gray-700 text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Contract number            </th>
                <th scope="col" class="px-6 py-3">
                    start date                </th>
                <th scope="col" class="px-6 py-3">
                    end date                </th>
                <th scope="col" class="px-6 py-3">
                    duration in days
                </th>
                <th scope="col" class="px-6 py-3">
                    <span class="sr-only">Edit</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Apple MacBook Pro 17"
                </th>
                <td class="px-6 py-4">
                    Silver
                </td>
                <td class="px-6 py-4">
                    Laptop
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Microsoft Surface Pro
                </th>
                <td class="px-6 py-4">
                    White
                </td>
                <td class="px-6 py-4">
                    Laptop PC
                </td>
                <td class="px-6 py-4">
                    $1999
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                </td>
            </tr>
            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Magic Mouse 2
                </th>
                <td class="px-6 py-4">
                    Black
                </td>
                <td class="px-6 py-4">
                    Accessories
                </td>
                <td class="px-6 py-4">
                    $99
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

    <!-- <footer class="bg-gray-900 p-8 mt-2">
        <div class="flex items-center justify-between">
            <a href="" class="">
                <img src="/images/cars.gif" class="h-8" alt="Flowbite Logo" />
            </a>
            <ul class="flex items-center text-sm font-medium text-gray-400 mb-0 ">
                <li>
                    <a href="/pages/voitures.php" class="hover:underline me-4 md:me-6">Voitures</a>
                </li>
                <li>
                    <a href="/pages/clients.php" class="hover:underline me-4 md:me-6">Clients</a>
                </li>
            </ul>
        </div>
        <hr class="my-6 border-gray-200 border-gray-700 " />
        <span class="block text-sm text-center text-gray-400">© 2024 <a href="https://flowbite.com/"
                class="hover:underline">Car Rental™</a>. All Rights Reserved.</span>
    </footer> -->





</body>

</html>