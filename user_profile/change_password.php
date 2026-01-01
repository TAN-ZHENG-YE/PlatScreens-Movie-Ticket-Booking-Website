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
        $_SESSION['message'] = 'All fields are required. Please try again.';
        header('Location: /homepage/index.php');
        exit;
    }

    // First verify the old password
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($old_password, $user['password'])) {
        $_SESSION['message'] = 'Current password is incorrect. Please try again.';
        header('Location: /homepage/index.php');
        exit;
    }

    // Update with new password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $update_stmt->bind_param("si", $hashed_password, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['message'] = 'Your password updated successfully.';
        header('Location: /homepage/index.php');
    } else {
        $_SESSION['message'] = 'Error updating password';
        header('Location: /homepage/index.php');
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
    <html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="/user_profile/edit_profile.css">
    </head>
    <body>
        <form id="changePasswordForm" class="profile-form" action="/user_profile/change_password.php" method="POST">
            <div class="form-group">
                <label for="old_password" class="form-label">Current Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" 
                           id="old_password" 
                           name="old_password" 
                           class="form-input"
                           required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="new_password" class="form-label">New Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-key input-icon"></i>
                    <input type="password" 
                           id="new_password" 
                           name="new_password" 
                           class="form-input"
                           required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-update">
                    <i class="fas fa-save"></i> Update Password
                </button>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="message-container">
                    <?php 
                    echo htmlspecialchars($_SESSION['message']);
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>
        </form>
    </body>
    </html>
    <?php
}
?>

