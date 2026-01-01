<?php
require '../global.php';
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="book_seat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <title>Movie Seat Booking</title>
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>

    <div class="selection-container">
        <div>
            <label for="location">Location:</label>
            <select id="locationSelect">
                <option value="TRX MALL">TRX MALL</option>
                <option value="ONE UTAMA MALL">ONE UTAMA MALL</option>
                <option value="PAVILLION BUKIT JALIL">PAVILLION BUKIT JALIL</option>
                <option value="SURIA KLCC">SURIA KLCC</option>
            </select>
        </div>
        <div>
            <label for="date">Date:</label>
            <input type="date" id="dateInput" />
        </div>
        <div>
            <label for="time">Time:</label>
            <select id="timeSelect">
                <option value="10:30:00">10:30 AM</option>
                <option value="15:30:00">3:30 PM</option>
                <option value="18:30:00">6:30 PM</option>
                <option value="21:30:00">9:30 PM</option>
            </select>
        </div>
    </div>

    <ul class="showcase">
        <li>
            <div class="seat available"></div>
            <small>Available</small>
        </li>
        <li>
            <div class="seat selected"></div>
            <small>Selected</small>
        </li>
        <li>
            <div class="seat sold"></div>
            <small>Sold</small>
        </li>
    </ul>

    <div class="container" id="seatingPlan">
        <div class="screen"></div>

        <div class="row">
            <div class="seat available" id="A1">A1</div>
            <div class="seat available" id="A2">A2</div>
            <div class="seat available" id="A3">A3</div>
            <div class="seat available" id="A4">A4</div>
            <div class="stairs">S</div>
            <div class="seat available" id="A5">A5</div>
            <div class="seat available" id="A6">A6</div>
            <div class="seat available" id="A7">A7</div>
            <div class="seat available" id="A8">A8</div>
        </div>

        <div class="row">
            <div class="seat available" id="B1">B1</div>
            <div class="seat available" id="B2">B2</div>
            <div class="seat available" id="B3">B3</div>
            <div class="seat available" id="B4">B4</div>
            <div class="stairs">T</div>
            <div class="seat available" id="B5">B5</div>
            <div class="seat available" id="B6">B6</div>
            <div class="seat available" id="B7">B7</div>
            <div class="seat available" id="B8">B8</div>
        </div>

        <div class="row">
            <div class="seat available" id="C1">C1</div>
            <div class="seat available" id="C2">C2</div>
            <div class="seat available" id="C3">C3</div>
            <div class="seat available" id="C4">C4</div>
            <div class="stairs">A</div>
            <div class="seat available" id="C5">C5</div>
            <div class="seat available" id="C6">C6</div>
            <div class="seat available" id="C7">C7</div>
            <div class="seat available" id="C8">C8</div>
        </div>

        <div class="row">
            <div class="seat available" id="D1">D1</div>
            <div class="seat available" id="D2">D2</div>
            <div class="seat available" id="D3">D3</div>
            <div class="seat available" id="D4">D4</div>
            <div class="stairs">I</div>
            <div class="seat available" id="D5">D5</div>
            <div class="seat available" id="D6">D6</div>
            <div class="seat available" id="D7">D7</div>
            <div class="seat available" id="D8">D8</div>
        </div>

        <div class="row">
            <div class="seat available" id="E1">E1</div>
            <div class="seat available" id="E2">E2</div>
            <div class="seat available" id="E3">E3</div>
            <div class="seat available" id="E4">E4</div>
            <div class="stairs">R</div>
            <div class="seat available" id="E5">E5</div>
            <div class="seat available" id="E6">E6</div>
            <div class="seat available" id="E7">E7</div>
            <div class="seat available" id="E8">E8</div>
        </div>

        <div class="row">
            <div class="seat available" id="F1">F1</div>
            <div class="seat available" id="F2">F2</div>
            <div class="seat available" id="F3">F3</div>
            <div class="seat available" id="F4">F4</div>
            <div class="stairs">S</div>
            <div class="seat available" id="F5">F5</div>
            <div class="seat available" id="F6">F6</div>
            <div class="seat available" id="F7">F7</div>
            <div class="seat available" id="F8">F8</div>
        </div>
    </div>

    <p class="text">
        You have selected <span id="count">0</span> seat(s) for a price of RM <span id="total">0.00</span>
    </p>

    <div class="button-container">
        <button id="goBack" onclick="goBack()">Go Back</button>
        <button id="next" disabled>Next</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="book_seat.js"></script>
</body>
</html>
