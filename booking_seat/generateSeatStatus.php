<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Import database parameters
require '../aws/getAppParameters.php';

// Create connection
$conn = new mysqli($db_url, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

// Fetch parameters from request
$location = $_GET['location'] ?? null;
$date = $_GET['date'] ?? null;
$time = $_GET['time'] ?? null;
$movie_id = $_GET['movie_id'] ?? null;

// Validate parameters
if (!$location || !$date || !$time || !$movie_id) {
    die(json_encode(['success' => false, 'message' => "Missing parameters"]));
}

// Auto-generate all seat statuses
$seatRows = ['A', 'B', 'C', 'D', 'E', 'F'];
$seatNumbers = range(1, 8);
$status = 'available'; // Default status for all seats

// Check if the movie exists for the given date and time
$currentDate = new DateTime();
$currentDateString = $currentDate->format('Y-m-d');

$movieQuery = "SELECT id FROM movies WHERE id = ? AND date_showing <= ? AND end_date >= ?";
$movieStmt = $conn->prepare($movieQuery);
$movieStmt->bind_param("sss", $movie_id, $currentDateString, $currentDateString);
$movieStmt->execute();
$movieResult = $movieStmt->get_result();

// If the movie is found, proceed to generate seat statuses
if ($movieResult->num_rows > 0) {
    while ($movie = $movieResult->fetch_assoc()) {
        $movieId = $movie['id']; // Using 'id' instead of 'movie_id'

        // Generate seat statuses for all seat combinations
        foreach (['TRX MALL'] as $loc) { // Use dynamic locations
            foreach (['10:30:00'] as $showTime) { // Use dynamic showtimes
                foreach ($seatRows as $row) {
                    foreach ($seatNumbers as $number) {
                        $seatId = "{$row}{$number}-{$movieId}-{$loc}-{$date}-{$showTime}";

                        // Check if the seat already exists in the database
                        $checkSeatQuery = "SELECT 1 FROM seat_status WHERE seat_id = ?";
                        $checkSeatStmt = $conn->prepare($checkSeatQuery);
                        $checkSeatStmt->bind_param("s", $seatId);
                        $checkSeatStmt->execute();
                        $checkSeatResult = $checkSeatStmt->get_result();

                        // If seat doesn't exist, insert it with default status 'available'
                        if ($checkSeatResult->num_rows === 0) {
                            $insertSeatQuery = "INSERT INTO seat_status (seat_id, movie_id, location, show_date, show_time, status) 
                                                VALUES (?, ?, ?, ?, ?, ?)";
                            $insertSeatStmt = $conn->prepare($insertSeatQuery);
                            $insertSeatStmt->bind_param("ssssss", $seatId, $movieId, $loc, $date, $showTime, $status);
                            if (!$insertSeatStmt->execute()) {
                                die(json_encode(['success' => false, 'message' => "Error inserting seat: " . $insertSeatStmt->error]));
                            }
                        }
                    }
                }
            }
        }
    }
    echo json_encode(['success' => true, 'message' => 'Seat statuses generated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Movie not found for the given date and time']);
}

$conn->close();
?>

