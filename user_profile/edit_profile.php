<?php
require '../global.php';
$conn = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if (empty($username) || empty($email) || empty($phone)) {
        $_SESSION['message'] = 'Missing required fields';
        header('Location: /homepage/index.php');
        exit;
    }

    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        $_SESSION['message'] = 'User not authenticated';
        header('Location: /homepage/index.php');
        exit;
    }

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $username, $email, $phone, $user_id);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['message'] = 'Profile updated successfully';
    } else {
        $_SESSION['message'] = 'Error updating profile';
    }

    $stmt->close();
    $conn->close();
    header('Location: /homepage/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit;
    }

    $stmt = $conn->prepare("SELECT username, email, phone FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/user_profile/edit_profile.css">
    
    <form id="editProfileForm" class="profile-form" action="/user_profile/edit_profile.php" method="POST">
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <div class="input-wrapper">
                <i class="fas fa-user input-icon"></i>
                <input type="text" 
                       id="username" 
                       name="username" 
                       class="form-input"
                       value="<?php echo htmlspecialchars($user['username']); ?>" 
                       required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-wrapper">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-input"
                       value="<?php echo htmlspecialchars($user['email']); ?>" 
                       required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="phone" class="form-label">Phone Number</label>
            <div class="input-wrapper">
                <i class="fas fa-phone input-icon"></i>
                <input type="tel" 
                       id="phone" 
                       name="phone" 
                       class="form-input"
                       value="<?php echo htmlspecialchars($user['phone']); ?>" 
                       required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-update">
                <i class="fas fa-save"></i> Update Profile
            </button>
        </div>

        <div id="updateMessage" class="message-container"></div>
    </form>
    <?php
    exit;
}
?>
