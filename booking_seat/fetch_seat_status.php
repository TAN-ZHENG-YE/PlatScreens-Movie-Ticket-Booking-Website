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
} else {
    error_log("Database connected successfully.");
}

// Fetch parameters from request
$location = $_GET['location'] ?? null;
$date = $_GET['date'] ?? null;
$time = $_GET['time'] ?? null;
$movie_id = $_GET['movie_id'] ?? null;

// Log received parameters
error_log("Location: $location, Date: $date, Time: $time, Movie ID: $movie_id");

// Validate parameters
if (!$location || !$date || !$time || !$movie_id) {
    die(json_encode(['success' => false, 'message' => "Missing parameters"]));
}

// Automatically generate seat statuses if not available
$currentDate = new DateTime();
$currentDateString = $currentDate->format('Y-m-d');

// Fetch movies currently showing (for the given movie_id, location, and time)
$movieQuery = "SELECT id FROM movies WHERE id = ? AND date_showing <= ? AND end_date >= ?";
$movieStmt = $conn->prepare($movieQuery);
if (!$movieStmt) {
    die(json_encode(['success' => false, 'message' => "Prepare failed: " . $conn->error]));
}
$movieStmt->bind_param("sss", $movie_id, $currentDateString, $currentDateString);
$movieStmt->execute();
$movieResult = $movieStmt->get_result();

// Check if there are any movies found
if ($movieResult->num_rows > 0) {
    $seatRows = ['A', 'B', 'C', 'D', 'E', 'F'];
    $seatNumbers = range(1, 8);
    $locations = ['TRX MALL', 'ONE UTAMA MALL', 'PAVILLION BUKIT JALIL', 'SURIA KLCC'];
    $showTimes = ['10:30:00', '15:30:00', '18:30:00', '21:30:00'];
    $status = 'available'; // Default status for all seats

    // Generate all seat statuses for each combination
    while ($movie = $movieResult->fetch_assoc()) {
        $movieId = $movie['id']; // Using 'id' instead of 'movie_id'
        
        foreach ($locations as $loc) {
            foreach ($showTimes as $showTime) {
                foreach ($seatRows as $row) {
                    foreach ($seatNumbers as $number) {
                        $seatId = "{$row}{$number}-{$movieId}-{$loc}-{$date}-{$showTime}";

                        // Check if seat already exists in the database
                        $checkSeatQuery = "SELECT 1 FROM seat_status WHERE seat_id = ?";
                        $checkSeatStmt = $conn->prepare($checkSeatQuery);
                        if (!$checkSeatStmt) {
                            die(json_encode(['success' => false, 'message' => "Prepare failed: " . $conn->error]));
                        }
                        $checkSeatStmt->bind_param("s", $seatId);
                        $checkSeatStmt->execute();
                        $checkSeatResult = $checkSeatStmt->get_result();

                        // If seat doesn't exist, insert it
                        if ($checkSeatResult->num_rows === 0) {
                            $insertSeatQuery = "INSERT INTO seat_status (seat_id, movie_id, location, show_date, show_time, status) 
                                                VALUES (?, ?, ?, ?, ?, ?)";
                            $insertSeatStmt = $conn->prepare($insertSeatQuery);
                            if (!$insertSeatStmt) {
                                die(json_encode(['success' => false, 'message' => "Prepare failed: " . $conn->error]));
                            }
                            $insertSeatStmt->bind_param("ssssss", $seatId, $movieId, $loc, $date, $showTime, $status);
                            if ($insertSeatStmt->execute()) {
                                error_log("Successfully inserted seat: $seatId");
                            } else {
                                die(json_encode(['success' => false, 'message' => "Error inserting seat: " . $insertSeatStmt->error]));
                            }
                        } else {
                            error_log("Seat already exists: $seatId");
                        }
                    }
                }
            }
        }
    }
} else {
    error_log("No movies found for the current date.");
}

// Fetch seat statuses for the requested movie
$sql = "SELECT seat_id, status FROM seat_status WHERE location = ? AND show_date = ? AND show_time = ? AND movie_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(['success' => false, 'message' => "Prepare failed: " . $conn->error]));
}

$stmt->bind_param("ssss", $location, $date, $time, $movie_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if seats are found
$seats = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $seats[] = $row;
    }
} else {
    error_log("No seat statuses found for the requested parameters.");
}

echo json_encode($seats, JSON_PRETTY_PRINT); // Pretty print for readability

$conn->close();
?>



