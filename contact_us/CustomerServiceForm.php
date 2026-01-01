<?php
require '../global.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Service Form</title>
    <link rel="stylesheet" href="CustomerServiceForm_Style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-fOqWmwb5k86Jq7p5KpD2pBbNSrdROv6+R7Qg4yfMVE3p25aYOG1xu+Q2VJ3zOxN75t7BTyF4fnyd7a2PJVgjCA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Pacifico&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>

<body>
    <?php include '../navbar/navbar.php'; ?>

    <section class="contact-container">
        <div class="qa-section">
            <h2>Contact Us</h2>
            <div class="qa">
                <h3 class="highlight-text">Please don't hesitate to contact us ! Here is our contact information...</h3>
            </div>
            <div class="qa">
                <h3>Our Email Support:</h3>
                <p>platscreens@gmail.com</p>
            </div>
            <div class="qa">
                <h3>Customer Hotline:</h3>
                <p>+603-8715 7588</p>
            </div>
            <div class="qa">
                <h3>Connect With Us On:</h3>
                <div class="social-icons">
                    <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com" target="_blank"><i class="fab fa-tiktok"></i></a>
                    <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.youtube.com" target="_blank"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <p class="service-hours">Customer Service Hours: Monday to Sunday 8.00am to 10.00pm</p>
        </div>

        <section class="contact">
            <h2>Ask Us Anything!</h2>
            <form id="contact-form" onsubmit="sendMail(event)">
                <div class="input-box">
                    <div class="input-field field">
                        <input type="text" placeholder="Your Full Name" id="name" class="item" autocomplete="off" 
                            value="<?php echo htmlspecialchars($username); ?>">
                        <div class="error-txt">Full name must be filled out!</div>
                    </div>
                    <div class="input-field field">
                        <input type="text" placeholder="Your Email Address" id="email" class="item" autocomplete="off"
                            value="<?php echo htmlspecialchars($userEmail); ?>">
                        <div class="error-txt email">Email Address can't be blank!</div>
                    </div>
                </div>
                <div class="input-box">
                    <div class="input-field field">
                        <input type="text" placeholder="Your Phone Number" id="phone" class="item" autocomplete="off"
                            value="<?php echo htmlspecialchars($phone); ?>">
                        <div class="error-txt">Phone Number must be filled out!</div>
                    </div>
                    <div class="input-field field">
                        <input type="text" placeholder="Subject" id="subject" class="item" autocomplete="off">
                        <div class="error-txt">Subject must be filled out!</div>
                    </div>
                </div>
                <div class="textarea-field field">
                    <textarea name="" id="message" cols="30" rows="10" placeholder="Your Message" class="item"
                        autocomplete="off"></textarea>
                    <div class="error-txt">Message can't be blank!</div>
                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </section>
    </section>

    <script src="CustomerServiceFormScript.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"
        integrity="sha512-9u8pl85n14DrE+6iNMMZ1v45R8YQzddMlgF3PwFc6DlUnQfSv8TMCtEtmC0Z9nG4RDY4Y7EaK8+Zo9MfoF8VcA=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    </script>
    <script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
    </script>
    <script type="text/javascript">
    (function(){
        emailjs.init({
            publicKey: "ZzZtEWEwKjXLZ3wiD",
        });
    })();
    </script>
</body>

</html>

