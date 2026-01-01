<?php
require '../global.php';
$conn = getDatabaseConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'] ?? null;
    
    if (!$user_id) {
        $_SESSION['message'] = 'User not authenticated';
        header('Location: /homepage/index.php');
        exit;
    }

    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if (empty($old_password) || empty($new_password)) {
        $_SESSION['message'] = 'All fields are required';
        header('Location: change_password.php');
        exit;
    }

    // First verify the old password
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($old_password, $user['password'])) {
        $_SESSION['message'] = 'Current password is incorrect';
        header('Location: change_password.php');
        exit;
    }

    // Update with new password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $update_stmt->bind_param("si", $hashed_password, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['message'] = 'Password updated successfully';
        header('Location: /homepage/index.php');
    } else {
        $_SESSION['message'] = 'Error updating password';
        header('Location: change_password.php');
    }

    $update_stmt->close();
    $stmt->close();
    $conn->close();
    exit;
}

// If it's a GET request, show the form
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login/login.php');
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="log_in.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
        <title>Change Password</title>
    </head>
    <body>
        <div class="wrapper">
            <form id="change-password-form" action="change_password.php" method="post">
                <h1>Change Password</h1>
                <div class="input-box">
                    <input type="password" name="old_password" placeholder="Old Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="new_password" placeholder="New Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button class="btn">Change Password</button>
            </form>
            <a href="login.html" class="back-btn">Back to Login</a>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="message-container">
                    <?php 
                    echo htmlspecialchars($_SESSION['message']);
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <script src="log_in.js"></script>
    </body>
    </html>
    <?php
}
?>


