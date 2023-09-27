<?php
// Define database connection parameters
$dbHost = '***REMOVED***';
$dbPort = ***REMOVED***;
$dbName = '***REMOVED***';
$dbUser = '***REMOVED***';
$dbPassword = '';

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
        $sql = "SELECT $graphType, timestamp FROM WeatherData WHERE Timestamp > DATE_SUB(NOW(), INTERVAL $amount $interval)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    } catch (PDOException $e) {
        throw $e;
    }
}
?>
