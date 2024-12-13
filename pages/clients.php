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
    $nom = $_POST['name'] ?? '';
    $adresse = $_POST['address'] ?? '';
    $tel = $_POST['number'] ?? '';

    if (empty($nom) || empty($adresse) || empty($tel)) {
        echo "Tous les champs doivent être remplis.";
    } else {
            $sqlInsert = "INSERT INTO Clients (Nom, Adresse, Tel) VALUES (?, ?, ?)";
            $params = array( $nom , $adresse, $tel);
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->execute( $params);

            $nom ="";
            $adresse="";
            $tel="";
            header('location:clients.php');
            echo "Voiture ajoutée avec succès.";
          
       
    }
}

if (isset($_GET['NumClient'])) {
    $NumClient = $_GET['NumClient'];

    $params = array($NumClient);
    $deleteClient = $conn->prepare("Delete FROM Clients WHERE NumClient = ?");

    $deleteClient->execute($params); 
    header('location:clients.php'); 
}

$sql = "SELECT * FROM Clients";
$clients =mysqli_query($conn, $sql);
$clients->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location de Voitures - Gestion des Clients</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="flex justify-between p-4">
        <a href="/index.php" id="cars">
            <img src="/images/cars.gif" alt="Cars">
        </a>
        <div class="lg:hidden" id="burger-icon">
            <img src="/images/menu.png" alt="Menu">
        </div>
        <div id="sidebar"
            class="shadow-xl fixed top-0 right-0 w-1/3 h-full bg-gray-200  z-50 transform translate-x-full duration-300">
            <div class="flex justify-end p-4">
                <button id="close-sidebar" class=" text-3xl">X</button>
            </div>
            <div class="flex flex-col items-center space-y-4 text-white">
                <a href="/pages/voitures.php" class="text-black text-lg">Cars</a>
                <a href="/pages/contrats.php" class="text-black text-lg">Contracts</a>
            </div>
        </div>
        <div class="hidden lg:flex justify-center space-x-4">
            <ul class="flex items-center text-sm font-medium text-gray-400 mb-0">
                <li><a href="/pages/voitures.php" class="hover:underline me-4 md:me-6">Cars</a></li>
                <li><a href="/pages/contrats.php" class="hover:underline me-4 md:me-6">Contracts</a></li>
            </ul>
        </div>
    </header>

    <section class="bg-blue-200 py-8 relative">
        <div class="px-6 flex items-center justify-between">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 mb-0">Liste des clients</h1>
            <button id="addClient" class="bg-blue-600 text-white py-3 px-3 rounded-full font-semibold text-lg hover:bg-blue-700 transition-colors duration-300">
                Ajouter un nouveau client
            </button>
        </div>
    </section>

    <div id="modalAdd" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
    <form method="post" class="max-w-sm mx-auto bg-white p-10 rounded-lg">
        <div class="mb-5">
            <label for="name" class="block mb-2 text-sm font-medium">Nom</label>
            <input type="text" id="name" name="name" class="border bg-gray-200 p-2 rounded-md" required />
        </div>
        <div class="mb-5">
            <label for="address" class="block mb-2 text-sm font-medium">Adresse</label>
            <input type="text" id="address" name="address" class="border bg-gray-200 p-2 rounded-md" required />
        </div>
        <div class="mb-5">
            <label for="number" class="block mb-2 text-sm font-medium">Numéro de téléphone</label>
            <input type="text" id="number" name="number" class="border bg-gray-200 p-2 rounded-md" required />
        </div>
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Ajouter</button>
        <button id="canceladd" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Annuler</button>
    </form>
</div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-2 my-8">
        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-gray-50 bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Numéro Client</th>
                    <th scope="col" class="px-6 py-3">Nom</th>
                    <th scope="col" class="px-6 py-3">Adresse</th>
                    <th scope="col" class="px-6 py-3">Numéro de téléphone</th>
                    <th scope="col" class="px-6 py-3"><span class="sr-only">Modifier</span></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <?php echo $client['NumClient']; ?>
                    </th>
                    <td class="px-6 py-4"><?php echo $client['Nom']; ?></td>
                    <td class="px-6 py-4"><?php echo $client['Adresse']; ?></td>
                    <td class="px-6 py-4"><?php echo $client['Tel']; ?></td>
                    <td class="px-6 py-4 text-right">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Modifier</a>
                        <a href="./clients.php?NumClient=<?php echo $client['NumClient']; ?>" class="font-medium text-red-600 dark:text-red-500 hover:underline">Supprimer</a>
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
        const addClient = document.getElementById("addClient");
        const addmodal = document.getElementById("modalAdd");
        const cancel = document.getElementById("canceladd");

        menu.addEventListener("click", () => {
            sidebar.classList.remove("translate-x-full");
            sidebar.classList.add("translate-x-0");
        });

        closeSidebar.addEventListener("click", () => {
            sidebar.classList.add("translate-x-full");
            sidebar.classList.remove("translate-x-0");
        });

        addClient.addEventListener("click", () => {
            addmodal.classList.toggle("hidden");
        });
        cancel.addEventListener("click", () => {
            addmodal.classList.toggle("hidden");
        });
    </script>
</body>

</html>