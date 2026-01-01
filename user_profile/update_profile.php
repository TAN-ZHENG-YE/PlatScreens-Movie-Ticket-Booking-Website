<?php
require '../global.php';
$conn = getDatabaseConnection();

header('Content-Type: application/json');

try {
    $userData = getUserSessionData();
    if (!$userData['is_authenticated']) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $userData['user_id'];
        $email = $_POST['email'] ?? '';
        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';
        $phoneNumber = $_POST['phoneNumber'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE users SET email = ?, first_name = ?, last_name = ?, phone_number = ? WHERE user_id = ?");
        $stmt->bind_param("ssssi", $email, $firstName, $lastName, $phoneNumber, $userId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        } else {
            throw new Exception('Error updating profile');
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    }
} catch (Exception $e) {
    error_log("Update Profile Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
} 