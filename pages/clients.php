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
            header('location:clients.php');
            echo "Voiture ajoutée avec succès.";  
    }
}


    
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $EditNumClient = $_GET['EditNumClient'];
    $nom = $_POST['name'];
    $adresse = $_POST['address'];
    $tel = $_POST['number'];
    $stmt = $conn->prepare("UPDATE Clients SET Nom = ?, Adresse = ?, Tel = ? WHERE NumClient = ?");
    $stmt->bind_param("sssi", $nom, $adresse, $tel, $EditNumClient);
    
    if ($stmt->execute()) {
        header('Location: clients.php'); 
    } else {
        echo "Error: " . $stmt->error;
    }
}

if (isset($_GET['EditNumClient'])) {
    $EditNumClient = $_GET['EditNumClient'];
    echo "<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editmodal = document.getElementById('modalEdit');
            editmodal.classList.remove('hidden')
        })
    </script>";


    $stmt = mysqli_query($conn, "SELECT * FROM Clients WHERE NumClient = $EditNumClient");
    
    $client = mysqli_fetch_assoc($stmt);

    
    $stmt->close();
}

if (isset($_GET['NumClient'])) {
    $NumClient = $_GET['NumClient'];

    $checkContractQuery = "SELECT COUNT(*) AS contract_count FROM Contrats WHERE NumClient = ?";
    $stmt = $conn->prepare($checkContractQuery);
    $stmt->bind_param("i", $NumClient);
    $stmt->execute();
    $stmt->bind_result($contractCount);
    $stmt->fetch();
    $stmt->close();

    if ($contractCount > 0) {
        $errorMessage = "Le client a un contrat actif et ne peut pas être supprimé.";
    } else {
        $deleteClient = $conn->prepare("DELETE FROM Clients WHERE NumClient = ?");
        $deleteClient->bind_param("i", $NumClient);
        $deleteClient->execute();
        header('location:clients.php');
    }
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
    <title>Car Rental - Customer Management</title>
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
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 mb-0">Customer List</h1>
            <button id="addClient" class="bg-blue-600 text-white py-3 px-3 rounded-full font-semibold text-lg hover:bg-blue-700 transition-colors duration-300">
            Add a new customer            </button>
        </div>
    </section>

    <div id="modalAdd" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
        <form method="post" class="max-w-sm mx-auto bg-white p-10 rounded-lg">
            <div class="mb-5">
                <label for="name" class="block mb-2 text-sm font-medium">Name</label>
                <input type="text" id="name" name="name" class="border bg-gray-200 p-2 rounded-md" 
                     required />
            </div>
            <div class="mb-5">
                <label for="address" class="block mb-2 text-sm font-medium">Adress</label>
                <input type="text" id="address" name="address" class="border bg-gray-200 p-2 rounded-md" 
                     required />
            </div>
            <div class="mb-5">
                <label for="number" class="block mb-2 text-sm font-medium">Phone number</label>
                <input type="text" id="number" name="number" class="border bg-gray-200 p-2 rounded-md" 
                     required />
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                <?php echo isset($client) ? 'Modifier' : 'Ajouter'; ?>
            </button>
            <button id="canceladd" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Annuler</button>
        </form>
    </div>

    <div id="modalEdit" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
        <form method="post" class="max-w-sm mx-auto bg-white p-10 rounded-lg">
            <div class="mb-5">
                <label for="name" class="block mb-2 text-sm font-medium">Name</label>
                <input type="text" id="name" name="name" class="border bg-gray-200 p-2 rounded-md" 
                    value="<?php echo isset($client) ? $client['Nom'] : ''; ?>" required />
            </div>
            <div class="mb-5">
                <label for="address" class="block mb-2 text-sm font-medium">Adress</label>
                <input type="text" id="address" name="address" class="border bg-gray-200 p-2 rounded-md" 
                    value="<?php echo isset($client) ? $client['Adresse'] : ''; ?>" required />
            </div>
            <div class="mb-5">
                <label for="number" class="block mb-2 text-sm font-medium">Phone number</label>
                <input type="text" id="number" name="number" class="border bg-gray-200 p-2 rounded-md" 
                    value="<?php echo isset($client) ? $client['Tel'] : ''; ?>" required />
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                Edit
            </button>
            <button id="cancelEdit" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Cancel</button>
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
                    <th scope="col" class="px-6 py-3">Customer Number</th>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Adress</th>
                    <th scope="col" class="px-6 py-3">Phone number</th>
                    <th scope="col" class="px-6 py-3"><span class="sr-only">Edit</span></th>
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
                        <a href="./clients.php?EditNumClient=<?php echo $client['NumClient']; ?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                        <a href="./clients.php?NumClient=<?php echo $client['NumClient']; ?>" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</a>
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
        const cancelAdd = document.getElementById("canceladd");
        const cancelEdit = document.getElementById("cancelEdit");

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
        cancelAdd.addEventListener("click", () => {
            addmodal.classList.toggle("hidden");
        });

        cancelEdit.addEventListener('click', () => {
            window.location.href = "/pages/clients.php";
        })

        

    </script>
</body>

</html>