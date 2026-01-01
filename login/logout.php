<?php
session_start();
session_unset();
session_destroy();
header('Location: /homepage/index.php'); // Redirect to home page
exit();
?> 