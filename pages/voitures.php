<?php
$host = 'localhost';
$dbname = 'locationvoitures';
$username = 'root';
$password = 'hamdi';

$conn = mysqli_connect($host, $username, $password, $dbname);

if(!$conn) {
    die("connection failed". mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NumImmatriculation = $_POST['NumImmatriculation'] ?? '';
    $Marque = $_POST['Marque'] ?? '';
    $Modele = $_POST['Modele'] ?? '';
    $Annee = $_POST['Annee'] ?? '';

    if (empty($NumImmatriculation) || empty($Marque) || empty($Modele) || empty($Annee)) {
        echo "Tous les champs doivent être remplis.";
    } else {
            $sqlInsert = "INSERT INTO Voitures (NumImmatriculation, Marque, Modele, Annee) 
                          VALUES (?, ?, ?, ?)";

            $params = array($NumImmatriculation, $Marque, $Modele, $Annee);
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->execute($params);

            $NumImmatriculation = "";
            $Marque = "";
            $Modele = "";
            $Annee = "";
            header('location:voitures.php');
            echo "Voiture ajoutée avec succès.";

    }
}

$sql = "SELECT * FROM Voitures";
$voitures = mysqli_query($conn, $sql);

$voitures->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location de Voitures</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="flex justify-between p-4">
        <a href="/index.html" id="cars">
            <img src="/images/cars.gif" alt="">
        </a>
        <div class="lg:hidden" id="burger-icon">
            <img src="/images/menu.png" alt="Menu">
        </div>
        <div id="sidebar"
            class="shadow-xl fixed top-0 right-0 w-1/3 h-full bg-gray-200 z-50 transform translate-x-full duration-300">
            <div class="flex justify-end p-4">
                <button id="close-sidebar" class="text-3xl">X</button>
            </div>
            <div class="flex flex-col items-center space-y-4 text-white">
                <a href="/pages/clients.php" class="text-black text-lg">Customers</a>
                <a href="/pages/contrats.php" class="text-black text-lg">Contracts</a>
            </div>
        </div>
        <div class="hidden lg:flex justify-center space-x-4">
            <ul class="flex items-center text-sm font-medium text-gray-400 mb-0">
                <li><a href="/pages/clients.php" class="hover:underline me-4 md:me-6">Customers</a></li>
                <li><a href="/pages/contrats.php" class="hover:underline me-4 md:me-6">Contracts</a></li>
            </ul>
        </div>
    </header>

    <section class="bg-blue-200 py-8 relative">
        <div class="px-6 flex items-center justify-between">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 mb-0">List of Cars </h1>
            <button id="addCar" class="bg-blue-600 text-white py-3 px-3 rounded-full font-semibold text-lg hover:bg-blue-700 transition-colors duration-300">
                Ajouter une voiture
            </button>
        </div>
    </section>

    <div id="modalAdd" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
        <form method="POST" class="max-w-sm mx-auto bg-white p-10 rounded-lg">
            <div class="mb-2">
                <label for="NumImmatriculation" class="block mb-2 text-sm font-medium">Numéro d'immatriculation</label>
                <input type="text" id="NumImmatriculation" name="NumImmatriculation" class="border bg-gray-200 p-2 rounded-md" required />
            </div>
            <div class="mb-5">
                <label for="Marque" class="block mb-2 text-sm font-medium">Marque</label>
                <input type="text" id="Marque" name="Marque" class="border bg-gray-200 p-2 rounded-md" required />
            </div>
            <div class="mb-5">
                <label for="Modele" class="block mb-2 text-sm font-medium">Modèle</label>
                <input type="text" id="Modele" name="Modele" class="border bg-gray-200 p-2 rounded-md" required />
            </div>
            <div class="mb-5">
                <label for="Annee" class="block mb-2 text-sm font-medium">Année</label>
                <input type="number" id="Annee" name="Annee" class="border bg-gray-200 p-2 rounded-md" required />
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                Ajouter
            </button>
            <button id="cancelAdd" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                Annuler
            </button>
        </form>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-2 my-8">
        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-gray-50 bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Numéro d'immatriculation</th>
                    <th scope="col" class="px-6 py-3">Marque</th>
                    <th scope="col" class="px-6 py-3">Modèle</th>
                    <th scope="col" class="px-6 py-3">Année</th>
                    <th scope="col" class="px-6 py-3">
                        <span class="sr-only">Modifier</span>
                        <span class="sr-only">Supprimer</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($voitures as $voiture): ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4"><?php echo $voiture['NumImmatriculation']; ?></td>
                        <td class="px-6 py-4"><?php echo $voiture['Marque']; ?></td>
                        <td class="px-6 py-4"><?php echo $voiture['Modele']; ?></td>
                        <td class="px-6 py-4"><?php echo $voiture['Annee']; ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Modifier</a>
                            <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline">Supprimer</a>
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
        const addCar = document.getElementById("addCar");
        const addmodal = document.getElementById("modalAdd");
        const cancel = document.getElementById("cancelAdd");

        menu.addEventListener("click", () => {
            sidebar.classList.remove("translate-x-full");
            sidebar.classList.add("translate-x-0");
        });

        closeSidebar.addEventListener("click", () => {
            sidebar.classList.add("translate-x-full");
            sidebar.classList.remove("translate-x-0");
        });

        addCar.addEventListener("click", () => {
            addmodal.classList.toggle("hidden");
        });

        cancel.addEventListener("click", () => {
            addmodal.classList.toggle("hidden");
        });
    </script>
</body>
</html>