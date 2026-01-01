<?php
require '../global.php';
$conn = getDatabaseConnection();

// Handle POST request (your existing login.php logic)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    // Your existing database logic
    $stmt = $conn->prepare("SELECT user_id, password, phone FROM users WHERE email = ? AND username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashedPassword, $phone);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['phone'] = $phone;

            echo json_encode(['status' => 'success', 'message' => 'Login successful.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid password.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No account found with the provided username and email.']);
    }

    $stmt->close();
    $conn->close();
    exit(); // Stop here for AJAX requests
}

// If it's not a POST request, display the login form (your existing login.html content)
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="log_in.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="wrapper">
        <form id="login-form" action="login.php" method="post">
            <h1>Login to</h1>
            <h1>PlatScreens</h1>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="remember-forgot">
                <label><input type="checkbox"> Remember me</label>
                <a href="change_password.php">Forgot Password?</a>
            </div>
            <button class="btn">Login</button>
            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register</a></p>
                <p>Are you an admin? <a href="/admin/admin/login.php">Click Here</a></p>
            </div>
        </form>
    </div>

    <script src="log_in.js"></script>
    <script>
        document.getElementById('login-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message); // Show success alert
                    setTimeout(() => {
                        window.location.href = '/homepage/index.php'; // Redirect to homepage
                    }, 100);
                } else {
                    alert(data.message); // Show error alert
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An unexpected error occurred.');
            });
        });
    </script>
</body>
</html>
