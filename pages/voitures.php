<?php
$host = 'localhost';
$dbname = 'locationvoitures';
$username = 'root';
$password = 'hamdi';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit();
}

$startDate = "";
$endDate = "";
$duration = "";
$customerNumber = "";
$registration = "";
$successMessage = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start'] ?? '';
    $endDate = $_POST['end'] ?? '';
    $duration = $_POST['durée'] ?? '';
    $customerNumber = $_POST['CustomerNumber'] ?? '';
    $registration = $_POST['Registration'] ?? '';

    if (empty($startDate) || empty($endDate) || empty($duration) || empty($customerNumber) || empty($registration)) {
        $errorMessage = "Tous les champs doivent être remplis.";
    } else {
        try {
            $sqlInsert = "INSERT INTO Contrats (DateDebut, DateFin, Duree, NumClient, NumImmatriculation) 
                          VALUES (:startDate, :endDate, :duration, :customerNumber, :registration)";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->bindParam(':startDate', $startDate);
            $stmtInsert->bindParam(':endDate', $endDate);
            $stmtInsert->bindParam(':duration', $duration);
            $stmtInsert->bindParam(':customerNumber', $customerNumber);
            $stmtInsert->bindParam(':registration', $registration);
            $stmtInsert->execute();

            $successMessage = "Contrat ajouté avec succès.";

            // Réinitialiser les champs
            $startDate = "";
            $endDate = "";
            $duration = "";
            $customerNumber = "";
            $registration = "";
        } catch (PDOException $e) {
            $errorMessage = "Erreur lors de l'ajout du contrat : " . $e->getMessage();
        }
    }
}

// Récupérer les clients
$sqlClients = "SELECT NumClient, Nom FROM Clients";
$stmtClients = $pdo->prepare($sqlClients);
$stmtClients->execute();
$clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les voitures
$sqlVoitures = "SELECT NumImmatriculation, Marque, Modele FROM Voitures";
$stmtVoitures = $pdo->prepare($sqlVoitures);
$stmtVoitures->execute();
$voitures = $stmtVoitures->fetchAll(PDO::FETCH_ASSOC);

// Récupération de la liste des contrats
$sql = "SELECT * FROM Contrats";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$contrats = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <a href="/index.html" id="cars">
        <img src="/images/cars.gif" alt="">
    </a>
    <div class="lg:hidden" id="burger-icon">
        <img src="/images/menu.png" alt="Menu">
    </div>
    <div id="sidebar" class="shadow-xl fixed top-0 right-0 w-1/3 h-full bg-gray-200 z-50 transform translate-x-full duration-300">
        <div class="flex justify-end p-4">
            <button id="close-sidebar" class="text-3xl">X</button>
        </div>
        <div class="flex flex-col items-center space-y-4 text-white">
            <a href="/pages/clients.php" class="text-black text-lg">Customers</a>
            <a href="/pages/contrats.php" class="text-black text-lg">Contracts</a>
        </div>
    </div>
    <div class="hidden lg:flex justify-center space-x-4">
        <ul class="flex items-center text-sm font-medium text-gray-400 mb-0 ">
            <li><a href="/pages/clients.php" class="hover:underline me-4 md:me-6">Customers</a></li>
            <li><a href="/pages/contrats.php" class="hover:underline me-4 md:me-6">Contracts</a></li>
        </ul>
    </div>
</header>

<section class="bg-blue-200 py-8 relative">
    <div class="px-6 flex items-center justify-between">
        <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 mb-0">Add a New Contract</h1>
    </div>
</section>

<div id="modalAdd" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
  <form method="post" class="max-w-sm mx-auto bg-white p-10 rounded-lg">
    <?php if ($successMessage): ?>
        <div class="bg-green-500 text-white p-4 rounded mb-4"><?php echo $successMessage; ?></div>
    <?php elseif ($errorMessage): ?>
        <div class="bg-red-500 text-white p-4 rounded mb-4"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <div class="mb-5">
      <label for="start" class="block mb-2 text-sm font-medium">Start Date</label>
      <input type="date" id="start" name="start" class="border bg-gray-200 p-2 rounded-md" required />
    </div>
    <div class="mb-5">
      <label for="end" class="block mb-2 text-sm font-medium">End Date</label>
      <input type="date" id="end" name="end" class="border bg-gray-200 p-2 rounded-md" required />
    </div>
    <div class="mb-5">
      <label for="durée" class="block mb-2 text-sm font-medium">Duration (in days)</label>
      <input type="number" id="durée" name="durée" class="border bg-gray-200 p-2 rounded-md" required />
    </div>

    <!-- Client Select -->
    <div class="mb-5">
        <label for="CustomerNumber" class="block mb-2 text-sm font-medium">Select Customer</label>
        <select id="CustomerNumber" name="CustomerNumber" class="border bg-gray-200 p-2 rounded-md" required>
            <option value="" disabled selected>Select a Customer</option>
            <?php foreach ($clients as $client): ?>
                <option value="<?php echo $client['NumClient']; ?>"><?php echo htmlspecialchars($client['Nom']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Vehicle Select -->
    <div class="mb-5">
        <label for="Registration" class="block mb-2 text-sm font-medium">Select Vehicle</label>
        <select id="Registration" name="Registration" class="border bg-gray-200 p-2 rounded-md" required>
            <option value="" disabled selected>Select a Vehicle</option>
            <?php foreach ($voitures as $voiture): ?>
                <option value="<?php echo $voiture['NumImmatriculation']; ?>"><?php echo htmlspecialchars($voiture['Marque'] . ' ' . $voiture['Modele']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5">Add</button>
    <button id="canceladd" type="button" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5">Cancel</button>
  </form>
</div>

<script>
    const menu = document.getElementById("burger-icon");
    const sidebar = document.getElementById("sidebar");
    const closeSidebar = document.getElementById("close-sidebar");
    const addCar = document.getElementById("addCar");
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

    addCar.addEventListener("click", () => {
        addmodal.classList.toggle("hidden");
    });

    cancel.addEventListener("click", () => {
        addmodal.classList.toggle("hidden");
    });
</script>

</body>
</html>
