<?php
require '../global.php';
$conn = getDatabaseConnection();

// Retrieve the movie ID from session storage
if (isset($_SESSION['selectedMovieId'])) {
    $movie_id = $_SESSION['selectedMovieId'];

    // Query to fetch movie details
    $sql = "SELECT title, cover_img, description, director, duration, language, genre, classification, release_date, cast, movie_background_image, trailer_yt_link 
            FROM movies 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $movie = $result->fetch_assoc();
    } else {
        echo "No details found for this movie.";
        exit();
    }
} else {
    echo "No movie selected.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Movie Website</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="movie_details.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <style>
    body {
      background-image: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('<?php echo $s3_url . '/images/' . $movie['movie_background_image']; ?>');
    }
  </style>
</head>
<body>
  <?php include '../navbar/navbar.php'; ?>
  <div class="container movie-details">
    <div class="row">
      <div class="col-md-8 left-box">
        <h1><?php echo $movie['title']; ?></h1>
        <p><span class="subtitle">Description:</span> <?php echo $movie['description']; ?></p>
        <p><span class="subtitle">Director:</span> <?php echo $movie['director']; ?></p>
        <p><span class="subtitle">Running Time:</span> <?php echo $movie['duration']; ?></p>
        <p><span class="subtitle">Language:</span> <?php echo $movie['language']; ?></p>
        <p><span class="subtitle">Genre:</span> <?php echo $movie['genre']; ?></p>
        <p><span class="subtitle">Classification:</span> <?php echo $movie['classification']; ?></p>
        <p><span class="subtitle">Release Date:</span> <?php echo $movie['release_date']; ?></p>
        <p><span class="subtitle">Casts:</span> <?php echo $movie['cast']; ?></p>
        <a href="<?php echo $movie['trailer_yt_link']; ?>" target="_blank" class="btn btn-primary">Watch Trailer</a>
        <a href="/booking_seat/book_seat.php" class="btn btn-outline-light">Book Now</a>
      </div>
      <div class="col-md-4">
      <img src="<?php echo $s3_url . '/images/' . $movie['cover_img']; ?>" class="movie-img">
      </div>
    </div>
  </div>

  <!--  Store the movie_id and movie_title in the client's session storage for next page usage-->
  <script>
      var movie_id = <?php echo json_encode($movie_id); ?>;
      var movie_title = <?php echo json_encode($movie['title']); ?>;
      sessionStorage.setItem("movie_id", movie_id);
      sessionStorage.setItem("movie_title", movie_title);
      console.log("Movie ID:", movie_id);
      console.log("Movie Title:", movie_title);
  </script>


</body>
</html>
