<?php
require '../global.php';
$conn = getDatabaseConnection();

// Get username from session
$username = $_SESSION['username'] ?? '';

if (empty($username)) {
    echo '<div class="error-message">User not authenticated</div>';
    exit;
}

try {
    $stmt = $conn->prepare("SELECT 
        invoice_id,
        userName,
        userPhone,
        userEmail,
        movie_title,
        chosen_location,
        showDate,
        showTime,
        seat_number,
        total_amount,
        payment_method
        FROM booking_info 
        WHERE userName = ?
        ORDER BY showDate DESC, showTime DESC");
        
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo '<div class="booking-history-container">';
        while ($booking = $result->fetch_assoc()) {
            echo '<div class="booking-card">';
            echo '<div class="booking-header">';
            echo '<span class="invoice-id">Invoice: ' . htmlspecialchars($booking['invoice_id']) . '</span>';
            echo '<span class="booking-date">' . htmlspecialchars(date('d M Y', strtotime($booking['showDate']))) . '</span>';
            echo '</div>';
            
            echo '<div class="booking-details">';
            echo '<div class="movie-title">' . htmlspecialchars($booking['movie_title']) . '</div>';
            echo '<div class="location">' . htmlspecialchars($booking['chosen_location']) . '</div>';
            echo '<div class="show-time">Show Time: ' . htmlspecialchars(date('h:i A', strtotime($booking['showTime']))) . '</div>';
            echo '<div class="seats">Seats: ' . htmlspecialchars($booking['seat_number']) . '</div>';
            echo '</div>';
            
            echo '<div class="booking-footer">';
            echo '<div class="amount">Total: ' . htmlspecialchars($booking['total_amount']) . '</div>';
            echo '<div class="payment-method">Paid via ' . htmlspecialchars($booking['payment_method']) . '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<div class="no-bookings">No booking history found.</div>';
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    echo '<div class="error-message">Error retrieving booking history: ' . $e->getMessage() . '</div>';
} finally {
    $conn->close();
}
?>

<link rel="stylesheet" href="/view_history/booking_history.css"> 