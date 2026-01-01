<?php
require '../global.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Confirm Payment</title>
    <link rel="stylesheet" href="payment_info.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>

    <h1>Payment Information</h1>

    <div id="selectedDetails">
        <li><strong>Movie:</strong> <span id="movieName">N/A</span></li>
        <li><strong>Location:</strong> <span id="cinemaLocation">N/A</span></li>
        <li><strong>Date:</strong> <span id="showDate">N/A</span></li>
        <li><strong>Time:</strong> <span id="showTime">N/A</span></li>
        <li><strong>Seat(s):</strong> <span id="seatNumber">N/A</span></li>
        <li><strong>Total:</strong> <span id="totalAmount">N/A</span></li>
    </div>


    <form id="payment-form">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required />

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" required />

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required />

        <label for="payment-method">Payment Method:</label>
        <select id="payment-method" name="payment-method" required>
            <option value="Paypal">PayPal</option>
            <option value="Online Bank Transfer">Online Bank Transfer</option>
            <option value="Touch-N-Go">Touch N Go</option>
        </select>

        <div id="bank-select-container" class="hidden">
            <label for="bank-select">Select Your Bank:</label>
            <select id="bank-select" name="bank-select">
                <option value="AmBank">AmBank</option>
                <option value="Bank Islam">Bank Islam</option>
                <option value="CIMB Bank">CIMB Bank</option>
                <option value="Hong Leong Bank">Hong Leong Bank</option>
                <option value="Maybank">MayBank</option>
                <option value="OCBC Bank">OCBC Bank</option>
                <option value="Public Bank">Public Bank</option>
                <option value="RHB Bank">RHB Bank</option>
            </select>
        </div>

        <div class="button-container">
            <button type="button" class="back" onclick="window.location.href='book_seat.php'">Go Back</button>
            <button type="submit" class="pay">Pay</button>
        </div>
    </form>

    <div id="popup" class="popup"></div>
    <script src="payment_info.js"></script>
</body>
</html>
