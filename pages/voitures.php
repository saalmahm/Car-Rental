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
            $sqlInsert = "INSERT INTO Voitures (NumImmatriculation, Marque, Modele, Annee) VALUES (?, ?, ?, ?)";
            $params = array($NumImmatriculation, $Marque, $Modele, $Annee);
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->execute($params);
            header('location:voitures.php');
            echo "Voiture ajoutée avec succès.";

    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $EditVoiture=$_GET['EditNumImmatriculation'];
    $NumImmatriculation= $_POST['NumImmatriculation'];
    $Marque = $_POST['Marque'];
    $Modele = $_POST['Modele'];
    $Annee = $_POST['Annee'] ;
    $stmt = $conn->prepare("UPDATE Voitures SET NumImmatriculation = ?, Marque = ?, Modele = ? , Annee = ?  WHERE NumImmatriculation = ?");
    $stmt->bind_param("ssss", $NumImmatriculation,  $Marque,  $Modele , $Annee);
    
    if ($stmt->execute()) {
        header('Location: voitures.php'); 
    } else {
        echo "Error: " . $stmt->error;
    }
}

if (isset($_GET['EditNumImmatriculation'])) {
    $EditNumImmatriculation = $_GET['EditNumImmatriculation'];
    echo "<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editmodal = document.getElementById('modalEdit');
            editmodal.classList.remove('hidden')
        })
    </script>";


    $stmt = mysqli_query($conn, "SELECT * FROM Voitures WHERE NumImmatriculation = '$EditNumImmatriculation'");
    
    $voiture = mysqli_fetch_assoc($stmt);

    
    $stmt->close();
}

if (isset($_GET['NumImmatriculation'])) {
    $NumImmatriculation = $_GET['NumImmatriculation'];

    $checkContractQuery = "SELECT COUNT(*) AS contract_count FROM Contrats WHERE NumImmatriculation = ?";
    $stmt = $conn->prepare($checkContractQuery);
    $stmt->bind_param("s", $NumImmatriculation);
    $stmt->execute();
    $stmt->bind_result($contractCount);
    $stmt->fetch();
    $stmt->close();

    if ($contractCount > 0) {
        $errorMessage = "La voiture est associée à un contrat actif et ne peut pas être supprimée.";
    } else {
        $params = array($NumImmatriculation);
        $deleteVoiture = $conn->prepare("DELETE FROM Voitures WHERE NumImmatriculation = ?");
        $deleteVoiture->execute($params);
        header('location:voitures.php');
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
    <title>Car Rental</title>
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
            Add a car            </button>
        </div>
    </section>

    <div id="modalAdd" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
        <form method="POST" class="max-w-sm mx-auto bg-white p-10 rounded-lg">
            <div class="mb-2">
                <label for="NumImmatriculation" class="block mb-2 text-sm font-medium">Registration number</label>
                <input type="text" id="NumImmatriculation" name="NumImmatriculation" class="border bg-gray-200 p-2 rounded-md" required />
            </div>
            <div class="mb-5">
                <label for="Marque" class="block mb-2 text-sm font-medium">Brand</label>
                <input type="text" id="Marque" name="Marque" class="border bg-gray-200 p-2 rounded-md" required />
            </div>
            <div class="mb-5">
                <label for="Modele" class="block mb-2 text-sm font-medium">Model</label>
                <input type="text" id="Modele" name="Modele" class="border bg-gray-200 p-2 rounded-md" required />
            </div>
            <div class="mb-5">
                <label for="Annee" class="block mb-2 text-sm font-medium">Year</label>
                <input type="number" id="Annee" name="Annee" class="border bg-gray-200 p-2 rounded-md" required />
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                Add
            </button>
            <button id="cancelAdd" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                Cancel
            </button>
        </form>
    </div>


    <div id="modalEdit" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
        <form method="POST" class="max-w-sm mx-auto bg-white p-10 rounded-lg">
            <div class="mb-2">
                <label for="NumImmatriculation" class="block mb-2 text-sm font-medium">Registration number</label>
                <input type="text" id="NumImmatriculation" name="NumImmatriculation" class="border bg-gray-200 p-2 rounded-md" 
                value="<?php echo isset($voiture) ? $voiture['NumImmatriculation'] : ''; ?>"   required />
            </div>
            <div class="mb-5">
                <label for="Marque" class="block mb-2 text-sm font-medium">Brand</label>
                <input type="text" id="Marque" name="Marque" class="border bg-gray-200 p-2 rounded-md" 
                value="<?php echo isset($voiture) ? $voiture['Marque'] : ''; ?>"  required />
            </div>
            <div class="mb-5">
                <label for="Modele" class="block mb-2 text-sm font-medium">Model</label>
                <input type="text" id="Modele" name="Modele" class="border bg-gray-200 p-2 rounded-md" 
                value="<?php echo isset($voiture) ? $voiture['Modele'] : ''; ?>"   required />
            </div>
            <div class="mb-5">
                <label for="Annee" class="block mb-2 text-sm font-medium">Year</label>
                <input type="number" id="Annee" name="Annee" class="border bg-gray-200 p-2 rounded-md"
                value="<?php echo isset($voiture) ? $voiture['Annee'] : ''; ?>"   required />
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
            Edit
            </button>
            <button id="cancelEdit" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                Cancel
            </button>
        </form>
    </div>

    <?php if (isset($errorMessage)): ?>
    <div class="bg-red-600 text-white p-6 m-4 flex justify-center item-center<">
        <?php echo $errorMessage; ?>
    </div>
<?php endif; ?>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-2 my-8">
        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-gray-50 bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Registration number</th>
                    <th scope="col" class="px-6 py-3">Brand</th>
                    <th scope="col" class="px-6 py-3">Model</th>
                    <th scope="col" class="px-6 py-3">Year</th>
                    <th scope="col" class="px-6 py-3">
                        <span class="sr-only">Edit</span>
                        <span class="sr-only">Delete</span>
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
                            <a href="./voitures.php?EditNumImmatriculation=<?php echo $voiture['NumImmatriculation']; ?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            <a href="./voitures.php?NumImmatriculation=<?php echo $voiture['NumImmatriculation']; ?>" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</a>
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
        const cancelEdit=document.getElementById("cancelEdit");

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
        cancelEdit.addEventListener('click',()=>{
            window.location.href="/pages/voitures.php"
        })
    </script>
</body>
</html>