document.addEventListener('DOMContentLoaded', function() {
  const bookingDetails = JSON.parse(sessionStorage.getItem('bookingDetails')) || {};


  // Update session storage with the complete bookingDetails
  sessionStorage.setItem('bookingDetails', JSON.stringify(bookingDetails));

  if (bookingDetails) {
    document.getElementById('movieName').textContent = bookingDetails.movie_title || 'N/A';
    document.getElementById('cinemaLocation').textContent = bookingDetails.location || 'N/A';
    document.getElementById('showDate').textContent = bookingDetails.date || 'N/A';
    document.getElementById('showTime').textContent = bookingDetails.time || 'N/A';

    // Extract original seat IDs
    const originalSeatIDs = bookingDetails.seats ? bookingDetails.seats.map(seat => seat.split('-')[0]) : [];
    document.getElementById('seatNumber').textContent = originalSeatIDs.join(',') || 'N/A';

    document.getElementById('totalAmount').textContent = bookingDetails.total || 'N/A';
  }

  document.getElementById('payment-method').addEventListener('change', function() {
    const bankSelectContainer = document.getElementById('bank-select-container');
    if (this.value === 'Online Bank Transfer') {
      bankSelectContainer.classList.remove('hidden');
    } else {
      bankSelectContainer.classList.add('hidden');
    }
  });

  document.getElementById('payment-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const name = document.getElementById('name').value;
    const phone = document.getElementById('phone').value;
    const email = document.getElementById('email').value;
    const paymentMethod = document.getElementById('payment-method').value;
    const bankSelect = document.getElementById('bank-select').value;

    if (!name || !phone || !email || !paymentMethod || (paymentMethod === 'online-bank-transfer' && !bankSelect)) {
      alert('Please fill out all required fields.');
      return;
    }

    const bookingDetailsObject = {
      userName: name,
      userPhone: phone,
      userEmail: email,
      payment: paymentMethod,
      bankName: bankSelect,
      movie_id: bookingDetails.movie_id,
      movie_title: bookingDetails.movie_title,
      location: bookingDetails.location,
      showDate: bookingDetails.date,
      showTime: bookingDetails.time,
      seats: bookingDetails.seats,
      total: bookingDetails.total
    };

    sessionStorage.setItem('bookingDetails', JSON.stringify(bookingDetailsObject));

    // Redirect based on payment method
    if (paymentMethod === 'Paypal') {
      window.location.href = 'paypal-demo.php';
    } else if (paymentMethod === 'Online Bank Transfer') {
      window.location.href = 'bank-demo.php';
    } else if (paymentMethod === 'Touch-N-Go') {
      window.location.href = 'touch-n-go-demo.php';
    }
  });
});

// If there are any redirects back to book_seat.html, update them to .php
function goBackToBooking() {
    window.location.href = 'book_seat.php';   
}



