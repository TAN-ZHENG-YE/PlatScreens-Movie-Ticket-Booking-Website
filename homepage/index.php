<?php
require '../global.php';
$conn = getDatabaseConnection();

$sql = "SELECT id, title, cover_img, description, date_showing, end_date FROM movies";
$result = $conn->query($sql);

$movies = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }
} else {
    echo "0 results";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./index.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <title>PlatScreens Homepage</title>
</head> 
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="sidebar">
        <i class="left-menu-icon fas fa-home" id="homeIcon"></i>
        <i class="left-menu-icon fas fa-search" id="searchIcon"></i>
    </div>

    <div class="container">
        <div class="featured-content" style="background: linear-gradient(to bottom, rgba(0,0,0,0), #151515), url('<?php echo $s3_url; ?>/images/godzilla-x-kong.jpg'); background-repeat: no-repeat; background-size: cover; background-position: center;">
            <img class="featured-title" src="<?php echo $s3_url; ?>/images/featuredTitle.png" alt="">
            <p class="featured-desc">After undoing Shimo's ice age with his atomic breath, Godzilla returns to rest in the Colosseum. Jia reunites with Andrews and alleviates her fears by choosing to stay with her adopted mother. Mothra restores the protective barrier of the Iwi's home and the portals.</p>
        </div>
        <div class="content-container">
            <?php if (!empty($movies)) { ?>
                <div class="movie-list-container">
                    <h1 class="movie-list-title" style="font-family: Verdana, sans-serif;">NEW RELEASES</h1>
                    <div class="movie-list-wrapper">
                        <div class="movie-list">
                            <?php foreach ($movies as $movie) { 
                                $current_date = date('Y-m-d');
                                if ($movie['date_showing'] <= $current_date && $movie['end_date'] >= $current_date) {
                            ?>
                                <div class="movie-list-item">
                                    <img class="movie-list-item-img" src="<?php echo $s3_url . '/images/' . $movie['cover_img']; ?>" alt="<?php echo $movie['title']; ?>">
                                    <span class="movie-list-item-title"><?php echo $movie['title']; ?></span>
                                    <p class="movie-list-item-desc"><?php echo $movie['description']; ?></p>
                                    <form action="set_movie.php" method="POST">
                                        <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
                                        <button type="submit" class="movie-list-item-button">Watch</button>
                                    </form>
                                </div>
                            <?php } } ?>
                        </div>
                        <i class="fas fa-chevron-right arrow"></i>
                    </div>
                </div>
                <div class="movie-list-container">
                    <h1 class="movie-list-title" style="font-family: Verdana, sans-serif;">COMING SOON</h1>
                    <div class="movie-list-wrapper">
                        <div class="movie-list">
                            <?php foreach ($movies as $movie) {
                                if ($movie['date_showing'] > $current_date) {
                            ?>
                                <div class="movie-list-item">
                                    <img class="movie-list-item-img" src="<?php echo $s3_url . '/images/' . $movie['cover_img']; ?>" alt="<?php echo $movie['title']; ?>">
                                    <span class="movie-list-item-title"><?php echo $movie['title']; ?></span>
                                    <p class="movie-list-item-desc"><?php echo $movie['description']; ?></p>
                                </div>
                            <?php } } ?>
                        </div>
                        <i class="fas fa-chevron-right arrow"></i>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="featured-content" style="background: linear-gradient(to bottom, rgba(0,0,0,0), #151515), url('<?php echo $s3_url; ?>/images/The-Watchers.webp'); background-repeat: no-repeat; background-size: cover; background-position: center;">
            <img class="featured-title" src="<?php echo $s3_url; ?>/images/Collection-Specific-Banner---Watchers-Photoroom.png" alt="">
            <p class="featured-desc">A 28-year-old artist gets stranded in an expansive, untouched forest in western Ireland. Finding shelter, she unknowingly becomes trapped alongside three strangers who are stalked by mysterious creatures every night.</p>
        </div>
    </div>
    <footer>
        <div class="footerBottom" style="background-color: black;">
            <p>Copyright &copy;2024; Designed by <span class="designer">PlatScreens</span></p>
        </div>
    </footer>
    <script src="app.js"></script>
    <script>
    // Wait for the document to fully load before adding event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Get references to the icon elements
        var searchIcon = document.getElementById('searchIcon');
        var homeIcon = document.getElementById('homeIcon');

        // Add click event listeners to each icon
        searchIcon.addEventListener('click', function() {
            window.location.href = '/searching';
        });

        homeIcon.addEventListener('click', function() {
            window.location.href = '/homepage';
        });
    });
    </script>
</body>
</html>
