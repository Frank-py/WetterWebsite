<?php
// Define database connection parameters
$dbHost = '***REMOVED***';
$dbPort = ***REMOVED***;
$dbName = '***REMOVED***';
$dbUser = '***REMOVED***';
$dbPassword = '***REMOVED***';

//$graphType = $_POST['graphType']; // Get the graphType from the POST request
//$amount = $_POST['amount']; // Get the amount from the POST request
//$interval = $_POST['interval']; // Get the interval from the POST request
$json = file_get_contents('php://input');
$data = json_decode($json);
$graphType = $data->graphType;
$amount = $data->amount;
$interval = $data->interval;




// Create a PDO connection
try {
    $pdo = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Define the getData function
function getData($graphType, $amount, $interval) {
    global $pdo;
    try {
        $sql = "SELECT $graphType, time FROM Data WHERE time > DATE_SUB(NOW(), INTERVAL $amount $interval) LIMIT 5;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    } catch (PDOException $e) {
        throw $e;
    }
}
try {
    $data = getData($graphType, $amount, $interval);
    echo json_encode($data);
} catch (PDOException $e) {
    // Handle any errors that may occur
    echo json_encode(['error' => $e->getMessage()]);
}
?>
