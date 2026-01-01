<?php
// init secret
require realpath($_SERVER["DOCUMENT_ROOT"]) . '/aws/getAppParameters.php';

// handle session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// get user session data
function getUserSessionData() {
    return [
        'username' => isset($_SESSION['username']) ? $_SESSION['username'] : '',
        'email' => isset($_SESSION['email']) ? $_SESSION['email'] : '',
        'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '',
        'phone' => isset($_SESSION['phone']) ? $_SESSION['phone'] : '',
        'is_authenticated' => isset($_SESSION['user_id'])
    ];
}

// get database connection
function getDatabaseConnection() {
    global $db_url, $db_user, $db_password, $db_name;

    $conn = new mysqli($db_url, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>