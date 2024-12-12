<?php
$host = 'localhost';
$dbname = 'locationvoitures';
$username = 'root';
$password = 'hamdi';

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start = $_POST['startd'] ?? '';
    $end = $_POST['endd'] ?? '';
    $idclient = $_POST['idclient'] ?? '';
    $duree = $_POST['duree'] ?? '';
    $matricul = $_POST['matricul'] ?? '';

    if (empty($start) || empty($end) || empty($duree) || empty($idclient) || empty($matricul)) {
        echo "Tous les champs doivent être remplis.";
    } else {
            $sqlInsert = "INSERT INTO Contrats (DateDebut, DateFin, Duree , NumClient , NumImmatriculation) VALUES (?, ?, ?,?, ?)";
            $params = array( $start , $end, $duree, $idclient, $matricul);
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->execute( $params);

            $start ="";
            $end="";
            $duree="";
            $idclient="";
            $matricul="";
            header('location:contrats.php');
            echo "contrats ajoutée avec succès.";
          
       
    }
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
        <button id="addNew" class="bg-blue-600 text-white py-3 px-3 rounded-full font-semibold text-lg hover:bg-blue-700 transition-colors duration-300">
    Add new Contras
</button>
    </div>
</section>

<div id="modalAdd" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
  <form method='post' class="max-w-sm mx-auto bg-white p-10 rounded-lg">
    <div class="mb-5">
      <label for="start" class="block mb-2 text-sm font-medium">start date</th>
      </label>
      <input name="startd" type="date" id="start" class="border bg-gray-200 p-2 rounded-md" required />
    </div>
    <div class="mb-5">
      <label for="end" class="block mb-2 text-sm font-medium">end date</th>
      </label>
      <input name="endd" type="date" id="end" class="border bg-gray-200 p-2 rounded-md" required />
    </div>
    <div class="mb-5">
      <label for="durée" class="block mb-2 text-sm font-medium">duration in days</label>
      <input name="duree" type="text" id="durée" class="border bg-gray-200 p-2 rounded-md" required />
    </div>
    <div class="mb-5">
      <label for="CustomerNumber" class="block mb-2 text-sm font-medium"> Customer</label>
      <select name="idclient" id="CustomerNumber" class="border bg-gray-200 p-2 rounded-md">
        <?php foreach($clients as $client){ ?>
            <option value="<?php echo $client['NumClient'] ?>"><?php echo $client['Nom'] ?></option>
        <?php } ?>
      </select>
    </div>
    <div class="mb-5">
      <label for="Registration" class="block mb-2 text-sm font-medium">Registration number</th>
      </label>
      <select name="matricul" id="Registration" class="border bg-gray-200 p-2 rounded-md" required >
        <?php foreach($voitures as $voiture){ ?>
        <option value="<?php echo $voiture['NumImmatriculation'] ?>"> <?php echo $voiture['NumImmatriculation'] ?> </option>
       <?php } ?>
      </select>
    </div>
    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add</button>
    <button id="canceladd" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cancel</button>
  </form>
</div>


<div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-2 my-8">
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-50 bg-gray-700 text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Contract number</th>   
                <th scope="col" class="px-6 py-3">start date</th>
                <th scope="col" class="px-6 py-3">end date</th>
                <th scope="col" class="px-6 py-3">duration in days </th>
                <th scope="col" class="px-6 py-3">Customer number</th>
                <th scope="col" class="px-6 py-3"> Registration number</th>
                </th>
                <th scope="col" class="px-6 py-3">
                    <span class="sr-only">Edit</span>
                </th>
            </tr>
        </thead>
        <tbody>
<?php foreach ($contrats as $contrat): ?> 
    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
        <?php echo ($contrat['NumContrat']); ?>
        </th>
        <td class="px-6 py-4">
        <?php echo ($contrat['DateDebut']); ?>
        </td>
        <td class="px-6 py-4">
        <?php echo ($contrat['DateFin']); ?>
        </td>
        <td class="px-6 py-4">
        <?php echo ($contrat['Duree']); ?>
        </td>
        <td class="px-6 py-4">
        <?php echo ($contrat['Nom']); ?>
        </td>
        <td class="px-6 py-4">
        <?php echo ($contrat['NumImmatriculation']); ?>
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
  document.addEventListener('DOMContentLoaded', () => {
    const menu = document.getElementById("burger-icon");
    const sidebar = document.getElementById("sidebar");
    const closeSidebar = document.getElementById("close-sidebar");
    const addNew = document.getElementById("addNew");
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

    addNew.addEventListener("click", () => {
      addmodal.classList.toggle("hidden");
    });

    cancel.addEventListener("click", () => {
      addmodal.classList.toggle("hidden");
    });
  });
</script>

</body>

</html>