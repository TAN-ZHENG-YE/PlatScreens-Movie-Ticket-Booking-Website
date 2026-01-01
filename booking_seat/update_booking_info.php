<?php
header('Content-Type: application/json');

try {
    // Check if POST request with JSON data
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || strpos($_SERVER['CONTENT_TYPE'], 'application/json') === false) {
        throw new Exception('Invalid request');
    }

    // Get JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    // Log the incoming data for debugging
    error_log("Incoming data: " . print_r($data, true));

    // Validate required fields
    if (!isset($data['userName'], $data['userPhone'], $data['userEmail'], $data['movie_id'], $data['movie_title'], $data['location'], $data['showDate'], $data['showTime'], $data['seats'], $data['total'], $data['payment'], $data['invoiceNumber'])) {
        throw new Exception('Invalid input data');
    }

    // import database parameters
    require '../aws/getAppParameters.php';

    // Create connection
    $conn = new mysqli($db_url, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }

    // Prepare SQL statement to insert into booking_info table
    $sql = "INSERT INTO booking_info (userName, userPhone, userEmail, movie_id, movie_title, chosen_location, showDate, showTime, seat_number, total_amount, payment_method, invoice_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssss", $data['userName'], $data['userPhone'], $data['userEmail'], $data['movie_id'], $data['movie_title'], $data['location'], $data['showDate'], $data['showTime'], $data['seats'], $data['total'], $data['payment'], $data['invoiceNumber']);

    // Execute SQL statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Failed to update booking info: ' . $stmt->error);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>


