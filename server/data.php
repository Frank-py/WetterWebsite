
<?php
$host = 'localhost';
$port = 8000;

$mysqli = new mysqli("***REMOVED***", "***REMOVED***", "***REMOVED***", "***REMOVED***");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['graphType']) && isset($_GET['amount']) && isset($_GET['interval'])) {
    $graphType = $mysqli->real_escape_string($_GET['graphType']);
    $amount = $mysqli->real_escape_string($_GET['amount']);
    $interval = $mysqli->real_escape_string($_GET['interval']);

    $data = getData($mysqli, $graphType, $amount, $interval);
    echo json_encode($data);
} else {
    echo "Invalid request.";
}

function getData($mysqli, $graphType, $amount, $interval) {
    $query = "SELECT $graphType, timestamp FROM WeatherData WHERE Timestamp > DATE_SUB(NOW(), INTERVAL $amount $interval)";
    $result = $mysqli->query($query);
    if (!$result) {
        die("Query failed: " . $mysqli->error);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $result->close();

    return $data;
}

$mysqli->close();
?>
