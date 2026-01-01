<?php
require '../global.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receipt</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      padding: 50px;
      background-color: #211f30; /* Background color for the whole page */
    }
    .receipt-container {
      max-width: 600px;
      margin: 0 auto;
      padding: 30px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); /* Soft shadow for the paper effect */
      border: 3px solid goldenrod; /* border */
    }
    h1 {
      font-size: 32px;
      margin-bottom: 20px;
      font-family: Verdana, sans-serif;
      font-weight: bold;
    }
    .receipt-info {
      text-align: left;
      margin-bottom: 30px;
      font-size: 1.2em;
    }
    .receipt-info p {
      margin-bottom: 10px;
    }
    .button-container {
      margin-top: 20px;
    }
    .button-container a {
      display: inline-block;
      padding: 10px 20px;
      text-decoration: none;
      color: white;
      border-radius: 5px;
      margin-right: 10px; /* Space between buttons */
    }
    .go-back-button {
      background-color: red;
    }
    .download-button {
      background-color: green;
    }
  </style>
</head>
<body>
  <div class="receipt-container">
    <h1>Receipt</h1>
    <div class="receipt-info">
      <p><strong>Invoice Number:</strong> <span id="invoiceNumber"></span></p>
      <p><strong>Receipt Created Date & Time:</strong> <span id="receiptDateTime"></span></p>
      <p><strong>User Name:</strong> <span id="userName"></span></p>
      <p><strong>User Phone Number:</strong> <span id="userPhone"></span></p>
      <p><strong>User Email:</strong> <span id="userEmail"></span></p>
      <p><strong>Chosen Movie Name:</strong> <span id="movieName"></span></p>
      <p><strong>Chosen Show Date & Time:</strong> <span id="chosenShowDateTime"></span></p>
      <p><strong>Chosen Cinema Location:</strong> <span id="chosenCinemaLocation"></span></p>
      <p><strong>Chosen Seat Number:</strong> <span id="seatNumber"></span></p>
      <p><strong>Total Amount Paid:</strong> <span id="totalAmount"></span></p>
      <p><strong>Payment Method:</strong> <span id="payMethod"></span></p>
      <p id="bankNameRow" style="display: none;"><strong>Paid via Bank:</strong> <span id="bankName"></span></p>
    </div>
    <div class="button-container">
      <a href="/homepage" class="go-back-button">Go Back to Homepage</a>
      <a href="#" id="downloadButton" class="download-button">Download</a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const bookingDetails = JSON.parse(sessionStorage.getItem('bookingDetails'));
      if (bookingDetails) {
        // Display data in the receipt page
        document.getElementById('invoiceNumber').textContent = bookingDetails.invoiceNumber || 'N/A';
        document.getElementById('receiptDateTime').textContent = getCurrentDateTime();
        document.getElementById('userName').textContent = bookingDetails.userName || 'N/A';
        document.getElementById('userPhone').textContent = bookingDetails.userPhone || 'N/A';
        document.getElementById('userEmail').textContent = bookingDetails.userEmail || 'N/A';
        document.getElementById('movieName').textContent = bookingDetails.movie_title || 'N/A';
        document.getElementById('chosenShowDateTime').textContent = `${bookingDetails.showDate} ${bookingDetails.showTime}` || 'N/A';
        document.getElementById('chosenCinemaLocation').textContent = bookingDetails.location || 'N/A';
        document.getElementById('payMethod').textContent = bookingDetails.payment || 'N/A';
        document.getElementById('bankName').textContent = bookingDetails.bankName || 'N/A';
        if (bookingDetails.payment === 'Online Bank Transfer') {
          document.getElementById('bankNameRow').style.display = 'block';
        }

        // Extract original seat IDs
        const originalSeatIDs = bookingDetails.seats.map(seat => seat.split('-')[0]);
        document.getElementById('seatNumber').textContent = originalSeatIDs.join(',') || 'N/A';

        document.getElementById('totalAmount').textContent = bookingDetails.total || 'N/A';

        // Download button functionality
        const downloadButton = document.getElementById('downloadButton');
        downloadButton.addEventListener('click', function(event) {
          event.preventDefault();

          // Show downloading message
          alert('Downloading the receipt...');

          // Generate PDF and download
          generatePDF();
        });

        function generatePDF() {
          const { jsPDF } = window.jspdf;
          const doc = new jsPDF();

          // Add content to PDF with styles
          doc.setFont('helvetica', 'bold');
          doc.setFontSize(24);
          doc.text('Receipt', 105, 20, { align: 'center' });

          doc.setFontSize(12);
          doc.setFont('helvetica', 'bold');
          doc.text('Invoice Number:', 20, 40);
          doc.setFont('helvetica', 'normal');
          doc.text(bookingDetails.invoiceNumber || 'N/A', 80, 40);

          doc.setFont('helvetica', 'bold');
          doc.text('Receipt Created Date & Time:', 20, 50);
          doc.setFont('helvetica', 'normal');
          doc.text(getCurrentDateTime(), 80, 50);

          doc.setFont('helvetica', 'bold');
          doc.text('User Name:', 20, 60);
          doc.setFont('helvetica', 'normal');
          doc.text(bookingDetails.userName || 'N/A', 80, 60);

          doc.setFont('helvetica', 'bold');
          doc.text('User Phone Number:', 20, 70);
          doc.setFont('helvetica', 'normal');
          doc.text(bookingDetails.userPhone || 'N/A', 80, 70);

          doc.setFont('helvetica', 'bold');
          doc.text('User Email:', 20, 80);
          doc.setFont('helvetica', 'normal');
          doc.text(bookingDetails.userEmail || 'N/A', 80, 80);

          doc.setFont('helvetica', 'bold');
          doc.text('Chosen Movie Name:', 20, 90);
          doc.setFont('helvetica', 'normal');
          doc.text(bookingDetails.movie_title || 'N/A', 80, 90);

          doc.setFont('helvetica', 'bold');
          doc.text('Chosen Show Date & Time:', 20, 100);
          doc.setFont('helvetica', 'normal');
          doc.text(`${bookingDetails.showDate} ${bookingDetails.showTime}` || 'N/A', 80, 100);

          doc.setFont('helvetica', 'bold');
          doc.text('Chosen Cinema Location:', 20, 110);
          doc.setFont('helvetica', 'normal');
          doc.text(bookingDetails.location || 'N/A', 80, 110);

          doc.setFont('helvetica', 'bold');
          doc.text('Chosen Seat Number:', 20, 120);
          doc.setFont('helvetica', 'normal');
          doc.text(originalSeatIDs.join(',') || 'N/A', 80, 120);

          doc.setFont('helvetica', 'bold');
          doc.text('Total Amount Paid:', 20, 130);
          doc.setFont('helvetica', 'normal');
          doc.text(bookingDetails.total || 'N/A', 80, 130);

          doc.setFont('helvetica', 'bold');
          doc.text('Payment Method:', 20, 140);
          doc.setFont('helvetica', 'normal');
          doc.text(bookingDetails.payment || 'N/A', 80, 140);

          if (bookingDetails.payment === 'Online Bank Transfer') {
            doc.setFont('helvetica', 'bold');
            doc.text('Paid via Bank:', 20, 150);
            doc.setFont('helvetica', 'normal');
            doc.text(bookingDetails.bankName || 'N/A', 80, 150);
          }

          // Save the PDF
          doc.save(`Receipt_${bookingDetails.invoiceNumber}.pdf`);
        }

        function getCurrentDateTime() {
          const now = new Date();
          return now.toLocaleString();
        }
      }
    });
  </script>
</body>
</html>







