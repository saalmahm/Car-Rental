<?php
$host = 'localhost';
$dbname = 'locationvoitures';
$username = 'root';
$password = 'hamdi';

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_contract'])) {
    $start = $_POST['startd'] ?? '';
    $end = $_POST['endd'] ?? '';
    $duree = $_POST['duree'] ?? '';
    $idclient = $_POST['idclient'] ?? '';
    $matricul = $_POST['matricul'] ?? '';

    if (empty($start) || empty($end) || empty($duree) || empty($idclient) || empty($matricul)) {
        echo "Tous les champs doivent être remplis.";
    } else {
        $sqlInsert = "INSERT INTO Contrats (DateDebut, DateFin, Duree, NumClient, NumImmatriculation) VALUES (?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("sssis", $start, $end, $duree, $idclient, $matricul);
        $stmtInsert->execute();
        header('Location: contrats.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['EditNumContrat'])) {
    $EditNumContrat = $_GET['EditNumContrat'];
    $start = $_POST['startd'];
    $end = $_POST['endd'];
    $duree = $_POST['duree'];
    $idclient = $_POST['idclient'];
    $matricul = $_POST['matricul'];

    if (empty($start) || empty($end) || empty($duree) || empty($idclient) || empty($matricul)) {
        echo "Tous les champs doivent être remplis.";
    } else {
        $stmtUpdate = $conn->prepare("UPDATE Contrats SET DateDebut = ?, DateFin = ?, Duree = ?, NumClient = ?, NumImmatriculation = ? WHERE NumContrat = ?");
        $stmtUpdate->bind_param("ssssis", $start, $end, $duree, $idclient, $matricul, $EditNumContrat);
        $stmtUpdate->execute();
        header('Location: contrats.php');
        exit;
    }
}

if (isset($_GET['NumContrat'])) {
    $NumContrat = $_GET['NumContrat'];
    $stmtDelete = $conn->prepare("DELETE FROM Contrats WHERE NumContrat = ?");
    $stmtDelete->bind_param("i", $NumContrat);
    $stmtDelete->execute();
    header('Location: contrats.php');
    exit;
}

$sql = "SELECT ct.*, c.Nom FROM Contrats ct JOIN Clients c ON ct.NumClient = c.NumClient";
$contratsResult = mysqli_query($conn, $sql);
$contrats = [];
if ($contratsResult && mysqli_num_rows($contratsResult) > 0) {
    while ($row = mysqli_fetch_assoc($contratsResult)) {
        $contrats[] = $row;
    }
}

$sql = "SELECT * FROM Clients";
$clientsResult = mysqli_query($conn, $sql);
$clients = [];
if ($clientsResult && mysqli_num_rows($clientsResult) > 0) {
    while ($row = mysqli_fetch_assoc($clientsResult)) {
        $clients[] = $row;
    }
}

$sql = "SELECT * FROM Voitures";
$voituresResult = mysqli_query($conn, $sql);
$voitures = [];
if ($voituresResult && mysqli_num_rows($voituresResult) > 0) {
    while ($row = mysqli_fetch_assoc($voituresResult)) {
        $voitures[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
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
                <button id="close-sidebar" class=" text-3xl">X</button>
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

    <section class="bg-blue-200 py-8 relative">
        <div class="px-6 flex items-center justify-between">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 mb-0">List of Contracts</h1>
            <button id="addNew" class="bg-blue-600 text-white py-3 px-3 rounded-full font-semibold text-lg hover:bg-blue-700 transition-colors duration-300">Add new Contract</button>
        </div>
    </section>

    <div id="modalAdd" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
        <form method="post" class="max-w-sm mx-auto bg-white p-10 rounded-lg">
            <div class="mb-5">
                <label for="start" class="block mb-2 text-sm font-medium">Start Date</label>
                <input name="startd" type="date" id="start" class="border bg-gray-200 p-2 rounded-md" required />
            </div>
            <div class="mb-5">
                <label for="end" class="block mb-2 text-sm font-medium">End Date</label>
                <input name="endd" type="date" id="end" class="border bg-gray-200 p-2 rounded-md" required />
            </div>
            <div class="mb-5">
                <label for="duree" class="block mb-2 text-sm font-medium">Duration in Days</label>
                <input name="duree" type="text" id="duree" class="border bg-gray-200 p-2 rounded-md" required />
            </div>
            <div class="mb-5">
                <label for="idclient" class="block mb-2 text-sm font-medium">Customer</label>
                <select name="idclient" id="idclient" class="border bg-gray-200 p-2 rounded-md">
                    <?php foreach ($clients as $client) { ?>
                        <option value="<?php echo $client['NumClient']; ?>"><?php echo $client['Nom']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-5">
                <label for="matricul" class="block mb-2 text-sm font-medium">Registration Number</label>
                <select name="matricul" id="matricul" class="border bg-gray-200 p-2 rounded-md">
                    <?php foreach ($voitures as $voiture) { ?>
                        <option value="<?php echo $voiture['NumImmatriculation']; ?>"><?php echo $voiture['NumImmatriculation']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" name="add_contract" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Add</button>
            <button type="button" id="canceladd" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Cancel</button>
        </form>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-2 my-8">
        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-gray-50 bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Contract Number</th>
                    <th scope="col" class="px-6 py-3">Start Date</th>
                    <th scope="col" class="px-6 py-3">End Date</th>
                    <th scope="col" class="px-6 py-3">Duration (days)</th>
                    <th scope="col" class="px-6 py-3">Client</th>
                    <th scope="col" class="px-6 py-3">Vehicle</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contrats as $contrat) { ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-white"><?php echo $contrat['NumContrat']; ?></td>
                        <td class="px-6 py-4 text-white"><?php echo $contrat['DateDebut']; ?></td>
                        <td class="px-6 py-4 text-white"><?php echo $contrat['DateFin']; ?></td>
                        <td class="px-6 py-4 text-white"><?php echo $contrat['Duree']; ?></td>
                        <td class="px-6 py-4 text-white"><?php echo $contrat['Nom']; ?></td>
                        <td class="px-6 py-4 text-white"><?php echo $contrat['NumImmatriculation']; ?></td>
                        <td class="px-6 py-4">
                     <a href="contrats.php?EditNumContrat=<?php echo $contrat['NumContrat']; ?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            <a href="contrats.php?NumContrat=<?php echo $contrat['NumContrat']; ?>" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($_GET['EditNumContrat'])) {
        $EditNumContrat = $_GET['EditNumContrat'];
        $stmtEdit = mysqli_query($conn, "SELECT * FROM Contrats WHERE NumContrat = $EditNumContrat");
        $editContrat = mysqli_fetch_assoc($stmtEdit);
    ?>
        <div id="modalEdit" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
            <form method="post" class="max-w-sm mx-auto bg-white p-10 rounded-lg">
                <input type="hidden" name="editNumContrat" value="<?php echo $editContrat['NumContrat']; ?>">
                <div class="mb-5">
                    <label for="start" class="block mb-2 text-sm font-medium">Start Date</label>
                    <input name="startd" type="date" class="border bg-gray-200 p-2 rounded-md" value="<?php echo $editContrat['DateDebut']; ?>" required />
                </div>
                <div class="mb-5">
                    <label for="end" class="block mb-2 text-sm font-medium">End Date</label>
                    <input name="endd" type="date" class="border bg-gray-200 p-2 rounded-md" value="<?php echo $editContrat['DateFin']; ?>" required />
                </div>
                <div class="mb-5">
                    <label for="duree" class="block mb-2 text-sm font-medium">Duration in Days</label>
                    <input name="duree" type="text" class="border bg-gray-200 p-2 rounded-md" value="<?php echo $editContrat['Duree']; ?>" required />
                </div>
                <div class="mb-5">
                    <label for="idclient" class="block mb-2 text-sm font-medium">Customer</label>
                    <select name="idclient" class="border bg-gray-200 p-2 rounded-md">
                        <?php foreach ($clients as $client) { ?>
                            <option value="<?php echo $client['NumClient']; ?>" <?php if ($client['NumClient'] == $editContrat['NumClient']) echo 'selected'; ?>><?php echo $client['Nom']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-5">
                    <label for="matricul" class="block mb-2 text-sm font-medium">Registration Number</label>
                    <select name="matricul" class="border bg-gray-200 p-2 rounded-md">
                        <?php foreach ($voitures as $voiture) { ?>
                            <option value="<?php echo $voiture['NumImmatriculation']; ?>" <?php if ($voiture['NumImmatriculation'] == $editContrat['NumImmatriculation']) echo 'selected'; ?>><?php echo $voiture['NumImmatriculation']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <button type="submit" class="bg-blue-700 text-white py-2 px-4 rounded-md">Save</button>
                <button type="button" id="cancelEdit" class="bg-gray-400 text-white py-2 px-4 rounded-md">Cancel</button>
            </form>
        </div>
    <?php } ?>

    <script>
        const addModal = document.getElementById('modalAdd');
        const canceladd = document.getElementById('canceladd');
        const editModal = document.getElementById('modalEdit');
        const cancelEdit = document.getElementById('cancelEdit');

        document.getElementById('addNew').onclick = () => {
            addModal.classList.remove('hidden');
        };

        canceladd.onclick = () => {
            addModal.classList.add('hidden');
        };

        cancelEdit.onclick = () => {
            editModal.classList.add('hidden');
        };
    </script>
</body>
</html>