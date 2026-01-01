<?php
require '../global.php';
$conn = getDatabaseConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create the registration table if it doesn't exist - silently
    $sql = "CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        user_type VARCHAR(50) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL
    )";
    $conn->query($sql);

    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username or email already exists
    $sql_check = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $username, $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Username or Email already exists. Please choose a different one.']);
        $stmt_check->close();
        $conn->close();
        exit();
    }
    $stmt_check->close();

    // Prepare an insert statement
    $stmt = $conn->prepare("INSERT INTO users (user_type, username, email, password, phone) VALUES (?, ?, ?, ?, ?)");
    $user_type = 'User'; 
    $stmt->bind_param("sssss", $user_type, $username, $email, $password, $phone);

    if ($stmt->execute() === TRUE) {
        session_start();
        $_SESSION['email'] = $email;
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['phone'] = $phone;

        echo json_encode(['status' => 'success', 'message' => 'New user registered successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error during registration: ' . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="log_in.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    
    <div class="wrapper">
        <form id="register-form" action="register.php" method="post">
            <h1>Register at PlatScreens</h1>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-box">
                <input type="phone" name="phone" placeholder="Phone" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <button class="btn">Register</button>
        </form>
        <a href="login.php" class="back-btn">Back to Login</a>
    </div>

    <script>
        document.getElementById('register-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission
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
                        window.location.href = '/login/login.php'; // Redirect to login
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
