<?php
require '../global.php';

try {
    $pdo = new PDO("mysql:host=$db_url;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Get search query from Ajax request
$searchTerm = isset($_GET['query']) ? $_GET['query'] : '';

// Prepare SQL statement
$search = $pdo->prepare("SELECT * FROM movies WHERE title LIKE :searchTerm LIMIT 100");
$search->bindValue(":searchTerm", $searchTerm . '%');
$search->execute();

// Fetch matching results
$results = $search->fetchAll(PDO::FETCH_ASSOC);

// Return results as JSON
echo json_encode($results);
?>



