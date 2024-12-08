
<?php
 $host = 'localhost';
 $dbname = 'locationvoitures';
 $username = 'root';
 $password = 'hamdi';
 
 try {
     $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    //  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 } catch (PDOException $e) {
     echo "Erreur de connexion : " . $e->getMessage();
     
 }
$sql = "SELECT * FROM Voitures";
$stmt = $pdo->prepare($sql);  
$stmt->execute();  

$voitures = $stmt->fetchAll(PDO::FETCH_ASSOC);
 ?>
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
        <a href="/index.html" id="cars">
            <img src="/images/cars.gif" alt="">
        </a>
        <div class="lg:hidden" id="burger-icon">
            <img src="/images/menu.png" alt="Menu">
        </div>
        <div id="sidebar"
            class="shadow-xl fixed top-0 right-0 w-1/3 h-full bg-gray-200  z-50 transform translate-x-full duration-300 ">
            <div class="flex justify-end p-4">
                <button id="close-sidebar" class=" text-3xl">X</button>
            </div>
            <div class="flex flex-col items-center space-y-4 text-white">
                <a href="/pages/clients.php" class="text-black text-lg">Customers</a>
                <a href="/pages/contrats.php" class="text-black text-lg">Contracts</a>
            </div>
        </div>
        <div class="hidden lg:flex justify-center space-x-4">
            <ul class="flex items-center text-sm font-medium text-gray-400 mb-0 ">
                <li>
                    <a href="/pages/clients.php" class="hover:underline me-4 md:me-6">Customers</a>
                </li>
                <li>
                    <a href="/pages/contrats.php" class="hover:underline me-4 md:me-6">Contracts</a>
                </li>
            </ul>
        </div>
    </header>
   

<section class="bg-blue-200 py-8 relative">
    <div class="px-6 flex items-center justify-between">
        <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 mb-0">List of cars</h1>
        <button id="addCar" class="bg-blue-600 text-white py-3 px-3 rounded-full font-semibold text-lg hover:bg-blue-700 transition-colors duration-300">
            Add a new car
        </button>
    </div>
    

    <div id="modalAdd" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
  <form class="max-w-sm mx-auto bg-white p-10 rounded-lg">
    <div class="mb-2">
      <label for="Resistration " class="block mb-2 text-sm font-medium ">Resistration number</label>
      <input type="text" id="Resistration" class="border bg-gray-200 p-2 rounded-md" required />
    </div>
    <div class="mb-5">
    <label for="Marque " class="block mb-2 text-sm font-medium ">brand</label>
    <input type="text" id="Marque" class="border bg-gray-200 p-2 rounded-md" required />
    </div>
    <div class="mb-5">
    <label for="model " class="block mb-2 text-sm font-medium ">model</label>
    <input type="text" id="model" class="border bg-gray-200 p-2 rounded-md" required />
    </div>
    <div class="mb-5">
    <label for="year " class="block mb-2 text-sm font-medium ">year</label>
    <input type="number" id="year" class="border bg-gray-200 p-2 rounded-md" required />
    </div>
    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add</button>
    <button id="canceladd" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cancel</button>
  </form>
</div>


</section>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-2 my-8">
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-50 bg-gray-700 text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Registration number                       </th>
                <th scope="col" class="px-6 py-3">
                    brand
                </th>
                <th scope="col" class="px-6 py-3">
                    model
                </th>
                <th scope="col" class="px-6 py-3">
                    year
                </th>
                <th scope="col" class="px-6 py-3">
                    <span class="sr-only">Edit</span>
                    <span class="sr-only">Delete</span>

                </th>
            </tr>
        </thead>
        <tbody>
<?php foreach ($voitures as $voiture): ?> 
    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
        <?php echo ($voiture['NumImmatriculation']); ?>
        </th>
        <td class="px-6 py-4">
        <?php echo ($voiture['Marque']); ?>
        </td>
        <td class="px-6 py-4">
        <?php echo ($voiture['Modele']); ?>
        </td>
        <td class="px-6 py-4">
        <?php echo ($voiture['Annee']); ?>
        </td>
        <td class="px-6 py-4 text-right">
            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit  </a>
            <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</a>

        </td>
    </tr>
<?php endforeach; ?>
</tbody>

    </table>
</div>
<script>
        const menu = document.getElementById("burger-icon");
        const sidebar = document.getElementById("sidebar");
        const closeSidebar = document.getElementById("close-sidebar");
        const addCar=document.getElementById("addCar");
        const addmodal =document.getElementById("modalAdd");
        const cancel=document.getElementById("canceladd");

        menu.addEventListener("click", () => {
            sidebar.classList.remove("translate-x-full");  
            sidebar.classList.add("translate-x-0");
        });

        closeSidebar.addEventListener("click", () => {
            sidebar.classList.add("translate-x-full");   
            sidebar.classList.remove("translate-x-0");    
        });

        addCar.addEventListener("click",()=>{
          addmodal.classList.toggle("hidden");
        })
        cancel.addEventListener("click",()=>{
          addmodal.classList.toggle("hidden");
        })
    </script>
</body>
</html>