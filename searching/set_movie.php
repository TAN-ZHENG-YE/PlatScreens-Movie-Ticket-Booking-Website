<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['movie_id'])) {
    $_SESSION['selectedMovieId'] = $_POST['movie_id'];
    header('Location: /movie_details/movie-details.php');
    exit();
} else {
    echo "Invalid request.";
}
?>
