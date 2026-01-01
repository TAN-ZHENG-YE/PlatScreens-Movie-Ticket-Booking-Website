document.addEventListener('DOMContentLoaded', function() {
  const failureButton = document.querySelector('.failure');
  const successButton = document.querySelector('.success');
  const popupMessage = document.getElementById('popupMessage');
  const popupText = document.getElementById('popupText');
  const confirmButton = document.getElementById('confirmButton');

  // Function to show popup message
  function showPopup(message) {
    popupText.textContent = message;
    popupMessage.style.display = 'block';
  }

  // Hide popup message
  confirmButton.addEventListener('click', function() {
    popupMessage.style.display = 'none';
  });

  // Event listener for failure button
  failureButton.addEventListener('click', function(event) {
    event.preventDefault();
    showPopup('Payment failed. Please try again later. Redirecting to Homepage...');
    setTimeout(function() {
      window.location.href = '/homepage'; // Replace with actual homepage URL
    }, 2000); // 2000 milliseconds = 2 seconds
  });

  // Event listener for success button
  successButton.addEventListener('click', function(event) {
    event.preventDefault();
    showPopup('Payment successful! Redirecting to receipt...');

    // Update seat status and booking info after 2 seconds
    setTimeout(function() {
      const bookingDetails = JSON.parse(sessionStorage.getItem('bookingDetails'));

      // Debug alert to view the booking details object
      console.log('Booking Details:', bookingDetails);

      const { userName, userPhone, userEmail, movie_id, movie_title, location, showDate, showTime, seats, total, payment} = bookingDetails;

      if (!userName || !userPhone || !userEmail || !movie_id || !movie_title || !location || !showDate || !showTime || !seats || !total || !payment) {
        console.error('Missing necessary information for updating seat status');
        showPopup('Failed to update seat status. Missing information.');
        return;
      }

      // Update seat status first
      fetch('update_seat_status.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          seats: seats,
          location: location,
          date: showDate,
          time: showTime,
          movie_id: movie_id
        })
      })
      .then(response => response.text()) // Get response as text
      .then(text => {
        try {
          const data = JSON.parse(text);
          console.log('Update seat status response:', data); // Log response for debugging
          if (data.success) {
            // Generate invoice number and update bookingDetails object
            const invoiceNumber = generateInvoiceNumber();
            bookingDetails.invoiceNumber = invoiceNumber;
            sessionStorage.setItem('bookingDetails', JSON.stringify(bookingDetails));

            // Extract original seat IDs
            const originalSeatIDs = bookingDetails.seats ? bookingDetails.seats.map(seat => seat.split('-')[0]) : [];
            
            // If seat status updated successfully, update booking info
            fetch('update_booking_info.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                userName: userName,
                userPhone: userPhone,
                userEmail: userEmail,
                movie_id: movie_id,
                movie_title: movie_title,
                location: location,
                showDate: showDate,
                showTime: showTime,
                seats: originalSeatIDs.join(','),
                total: total,
                payment: payment,
                invoiceNumber: invoiceNumber
              })
            })
            .then(response => response.text()) // Get response as text
            .then(text => {
              try {
                const data = JSON.parse(text);
                console.log('Update booking info response:', data); // Log response for debugging
                if (data.success) {
                  console.log('Redirecting to receipt page...');
                  window.location.href = 'receipt.php'; // Changed from .html to .php
                } else {
                  showPopup('Failed to update booking info. Please contact support.');
                }
              } catch (e) {
                console.error('Error parsing JSON:', e);
                console.error('Response text:', text);
                showPopup('Error updating booking info.');
              }
            })
            .catch(error => {
              console.error('Error updating booking info:', error);
              showPopup('Error updating booking info.');
            });
          } else {
            showPopup(`Failed to update seat status. Errors: ${data.errors ? data.errors.join(', ') : 'Unknown error'}`);
          }
        } catch (e) {
          console.error('Error parsing JSON:', e);
          console.error('Response text:', text);
          showPopup('Error updating seat status.');
        }
      })
      .catch(error => {
        console.error('Error updating seat status:', error);
        showPopup('Error updating seat status.');
      });
    }, 2000); // 2000 milliseconds = 2 seconds
  });

  function generateInvoiceNumber() {
    const now = new Date();
    const year = now.getFullYear();
    const month = ('0' + (now.getMonth() + 1)).slice(-2);
    const day = ('0' + now.getDate()).slice(-2);
    const hours = ('0' + now.getHours()).slice(-2);
    const minutes = ('0' + now.getMinutes()).slice(-2);
    const seconds = ('0' + now.getSeconds()).slice(-2);
    return `${year}${month}${day}${hours}${minutes}${seconds}`;
  }
});
