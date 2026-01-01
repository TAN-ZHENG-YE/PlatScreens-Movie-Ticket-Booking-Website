<?php
require '../global.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us</title>
  <link rel="stylesheet" href="about_us.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>

<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="about-container">
      <section class="hero">
        <div class="heading">
          <h1>About Us</h1>
        </div>
        <div class="container">
          <div class="hero-content">
            <h2>Welcome To Our Cinema Website</h2>
            <p>
              Welcome to PlatScreens Cinema! We are a renowned cinema chain with a decade of operation in Malaysia, offering exceptional cinematic experiences.
              With our presence in 8 branches across various states in Malaysia and continuous expansion, PlatScreens Cinema is proud to be one of the largest cinema chain in the country. We take pride in featuring the most advanced cinema technology available, ensuring that our audiences enjoy top-notch visual and audio quality with every screening.
              PlatScreens Cinema is not just a destination to watch movies; it's a place where friends, families, and communities gather to create lasting memories. Whether you seek family fun, a luxurious cinematic adventure, a great night out, or immersive entertainment, PlatScreens Cinema offers it all. Join us for a cinematic journey like no other, where every visit promises excitement, entertainment, and cherished moments.
            </p>
            <button class="cta-button" onclick="location.href='https://www.youtube.com/watch?v=rivAoauGiRo'">Learn More</button>    
          </div>
          <div class="hero-image">
            <img src="<?php echo $s3_url; ?>/images/cinema_about_us.jpg" alt="Cinema About Us">
          </div>
        </div>
      </section>
      
      <script>
        document.querySelector('.cta-button').addEventListener('click', function() {
          window.location.href = 'https://www.youtube.com/watch?v=rivAoauGiRo';
        });
      </script>
       
      <script src="about_us.js"></script>
    </div>
</body>
</html>
