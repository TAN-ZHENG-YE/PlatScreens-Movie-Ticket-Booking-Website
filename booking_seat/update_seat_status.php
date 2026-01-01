<?php
header('Content-Type: application/json');

// Check if POST request with JSON data
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_SERVER['CONTENT_TYPE'] !== 'application/json') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['seats']) && is_array($data['seats']) && isset($data['location']) && isset($data['date']) && isset($data['time']) && isset($data['movie_id'])) {

    // import database parameters
    require '../aws/getAppParameters.php';

    // Create connection
    $conn = new mysqli($db_url, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
    }

    // Begin transaction
    $conn->begin_transaction();
    
    $success = true;

    // Prepare and execute SQL update statements
    foreach ($data['seats'] as $seat_id) {
        $sql = "UPDATE seat_status SET status = 'sold' WHERE seat_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $seat_id);

        if (!$stmt->execute()) {
            $success = false;
            break;
        }
    }

    if ($success) {
        $conn->commit();
        echo json_encode(['success' => true]);
    } else {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => "Failed to update seat status"]);
    }

    // Close prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => "Invalid input data"]);
}
?>
