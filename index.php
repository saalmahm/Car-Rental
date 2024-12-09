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
            <img src="images/cars.gif" alt="">
        </a>
        <div class="lg:hidden" id="burger-icon">
            <img src="images/menu.png" alt="Menu">
        </div>
        <div id="sidebar"
            class="shadow-xl fixed top-0 right-0 w-1/3 h-full bg-gray-200  z-50 transform translate-x-full duration-300 ">
            <div class="flex justify-end p-4">
                <button id="close-sidebar" class=" text-3xl">×</button>
            </div>
            <div class="flex flex-col items-center space-y-4 text-white">
                <a href="#Contact" class="text-black text-lg">Contact us</a>
                <a href="/pages/voitures.php" class="text-black text-lg">Cars</a>
                <a href="/pages/clients.php" class="text-black text-lg">Customers</a>
                <a href="/pages/contrats.php" class="text-black text-lg">Contracts</a>

            </div>
        </div>
        <div class="hidden lg:flex justify-center space-x-4">
            <ul class="flex items-center text-sm font-medium text-gray-400 mb-0 ">
                <li>
                    <a href="#Contact" class="hover:underline me-4 md:me-6">Contact us</a>
                </li>
                <li>
                    <a href="/pages/voitures.php" class="hover:underline me-4 md:me-6">Cars</a>
                </li>
                <li>
                    <a href="/pages/clients.php" class="hover:underline me-4 md:me-6">Customers</a>
                </li>
                <li>
                    <a href="/pages/contrats.php" class="hover:underline me-4 md:me-6">Contracts</a>
                </li>

            </ul>
        </div>
    </header>
    <section class="bg-blue-200 py-20 relative">
        <div class="absolute right-0 top-20 ">
            <img src="images/car-section.png" alt="Voiture" >
        </div>
            <div class="px-6 lg:right-2">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 mb-4">Car Rental</h1>
            <p class="text-lg sm:text-xl text-gray-700 mb-8">Welcome to our fleet of cars.</p>
            <a href="/pages/voitures.php" class="inline-block bg-blue-600 text-white py-3 px-6 rounded-full font-semibold text-lg hover:bg-blue-700 transition-colors duration-300">
                View Our Cars
            </a>
        </div>
    </section>
    
    
    
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

<section class="bg-white dark:bg-white" id="Contact">
    <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
        <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-center text-gray-900 dark:text-gray-900">Contact Us</h2>
        <p class="mb-8 lg:mb-16 font-light text-center text-gray-700 dark:text-gray-700 sm:text-xl">Got a technical issue? Want to send feedback about a beta feature? Need details about our Business plan? Let us know.</p>
        <form action="#" class="space-y-8">
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">Your email</label>
                <input type="email" id="email" class="shadow-sm bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-white dark:border-gray-300 dark:placeholder-gray-500 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="name@flowbite.com" required>
            </div>
            <div>
                <label for="subject" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">Subject</label>
                <input type="text" id="subject" class="block p-3 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-white dark:border-gray-300 dark:placeholder-gray-500 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="Let us know how we can help you" required>
            </div>
            <div class="sm:col-span-2">
                <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">Your message</label>
                <textarea id="message" rows="6" class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg shadow-sm border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-white dark:border-gray-300 dark:placeholder-gray-500 dark:text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Leave a comment..."></textarea>
            </div>
            <button type="submit" class="py-3 px-5 text-sm font-medium text-center text-white rounded-lg bg-blue-600 sm:w-fit hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Send message</button>
        </form>
    </div>
  </section>
  
  
 
<footer class="bg-gray-900 p-8 mt-2">
    <div class="flex items-center justify-between">
        <a href="" class="">
            <img src="images/cars.gif" class="h-8" alt="Flowbite Logo" />
        </a>
        <ul class="flex items-center text-sm font-medium text-gray-400 mb-0 ">
            <li>
                <a href="#Contact" class="hover:underline me-4 md:me-6">Contact us</a>
            </li>
            <li>
                <a href="pages/voitures.php" class="hover:underline me-4 md:me-6">Cars</a>
            </li>
            <li>
                <a href="pages/clients.php" class="hover:underline me-4 md:me-6">Customers</a>
            </li>
            <li>
                <a href="pages/contrats.php" class="hover:underline me-4 md:me-6">Contracts</a>
            </li>
        </ul>
    </div>
    <hr class="my-6 border-gray-200 border-gray-700 " />
    <span class="block text-sm text-center text-gray-400">© 2024 <a href="https://flowbite.com/" class="hover:underline">Car Rental™</a>. All Rights Reserved.</span>
</footer>





</body>

</html>