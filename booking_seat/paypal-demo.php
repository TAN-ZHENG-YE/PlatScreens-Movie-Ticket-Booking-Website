<?php
require '../global.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PayPal Demo Page</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        text-align: center;
        padding: 50px;
        background-color: #b0b7d7d6;
      }
      img {
        width: 100px;
      }
      h1 {
        font-size: 32px;
        margin-bottom: 20px;
        font-family: Verdana, sans-serif;
        font-weight: bold;
      }
      p {
        margin-bottom: 30px;
        font-size: 1.2em;
      }
      .button-group {
        display: flex;
        justify-content: center;
        gap: 20px;
      }
      .button-group a {
        display: inline-block;
        padding: 10px 20px;
        text-decoration: none;
        color: white;
        border-radius: 5px;
      }
      .failure {
        background-color: #e74c3c;
      }
      .success {
        background-color: #2ecc71;
      }
      /* Styling for popup message */
      .popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 20px;
        border-radius: 10px;
        display: none;
        z-index: 1000;
      }        
    </style>
  </head>
  <body>
    <h1><img src="<?php echo $s3_url; ?>/images/PayPal.png" alt="PayPal Logo" /> PayPal Demo Page</h1>
    <p>This is just a demo PayPal page. You can choose whether to make this payment successful or not.</p>
    <div class="button-group">
      <a href="#" class="failure">Failure</a>
      <a href="#" class="success">Successful</a>
    </div>
    
    <!-- Popup message -->
    <div class="popup" id="popupMessage">
      <p id="popupText"></p>
      <button id="confirmButton">Confirm</button>
    </div>

    <script src="bank-demo.js"></script>
  </body>
</html>

