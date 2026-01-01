function getCurrentDate() {
    const today = new Date();
    const year = today.getFullYear();
    const month = ('0' + (today.getMonth() + 1)).slice(-2);
    const day = ('0' + today.getDate()).slice(-2);
    return `${year}-${month}-${day}`;
}

document.addEventListener('DOMContentLoaded', function() {
    const seats = document.querySelectorAll('.row .seat:not(.sold)');
    const count = document.getElementById('count');
    const total = document.getElementById('total');
    const nextButton = document.getElementById('next');
    const locationSelect = document.getElementById('locationSelect');
    const dateInput = document.getElementById('dateInput');
    dateInput.setAttribute('min', getCurrentDate());
    const timeSelect = document.getElementById('timeSelect');
    const seatingPlan = document.getElementById('seatingPlan');

    // Retrieve movie_id & movie_title from session storage
    const movie_id = sessionStorage.getItem("movie_id");
    console.log("Movie ID:", movie_id);

    const movie_title = sessionStorage.getItem("movie_title");
    console.log("Movie Title:", movie_title);

    // Initial fetch seat status
    fetchSeatStatus();

    // Event listeners for changing location, date, and time
    locationSelect.addEventListener('change', function() {
        fetchSeatStatus();  // Call this to fetch seat status based on user selection
        autoGenerateSeatStatus();  // Call this to auto-generate seat statuses based on selection
    });
    dateInput.addEventListener('change', function() {
        fetchSeatStatus();  // Call this to fetch seat status based on user selection
        autoGenerateSeatStatus();  // Call this to auto-generate seat statuses based on selection
    });
    timeSelect.addEventListener('change', function() {
        fetchSeatStatus();  // Call this to fetch seat status based on user selection
        autoGenerateSeatStatus();  // Call this to auto-generate seat statuses based on selection
    });

    // Event listener for selecting seats
    seats.forEach(seat => {
        seat.addEventListener('click', function() {
            if (!seat.classList.contains('sold')) {
                seat.classList.toggle('selected');
                updateSelectedCount();
            }
        });
    });

    // Function to fetch seat status based on selected criteria
    function fetchSeatStatus() {
        const selectedLocation = locationSelect.value;
        const selectedDate = dateInput.value;
        const selectedTime = timeSelect.value;

        console.log('Fetching seats with params:');
        console.log('Selected Location:', selectedLocation);
        console.log('Selected Date:', selectedDate);
        console.log('Selected Time:', selectedTime);

        if (selectedLocation && selectedDate && selectedTime && movie_id) {
            fetch(`fetch_seat_status.php?movie_id=${movie_id}&location=${selectedLocation}&date=${selectedDate}&time=${selectedTime}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Seat status fetched:', data);
                    updateSeatIDs(movie_id, selectedLocation, selectedDate, selectedTime);
                    updateSeatImages(data);
                })
                .catch(error => console.error('Error fetching seat status:', error));
        } else {
            console.error('Missing parameters for fetching seat status.');
        }
    }

    // Function to trigger seat status auto-generation dynamically based on user selections
    function autoGenerateSeatStatus() {
        const selectedLocation = locationSelect.value;
        const selectedDate = dateInput.value;
        const selectedTime = timeSelect.value;

        // Trigger seat status generation via API when location, date, or time changes
        if (selectedLocation && selectedDate && selectedTime && movie_id) {
            let apiUrl = `/booking_seat/generateSeatStatus.php?location=${selectedLocation}&date=${selectedDate}&time=${selectedTime}&movie_id=${movie_id}`;
            
            // Trigger AJAX call to auto-generate seat statuses
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    console.log("Seat status generation triggered:", data);
                    if (data.success) {
                        console.log("Seat statuses generated and stored in the database.");
                    } else {
                        console.error("Error generating seat statuses:", data.message);
                    }
                })
                .catch(error => console.error("AJAX Error:", error));
        }
    }

    // Function to update seat images based on fetched data
    function updateSeatImages(seatData) {
        seats.forEach(seat => {
            seat.classList.remove('available', 'selected', 'sold');
            seat.classList.add('available');
            const seatDataEntry = seatData.find(data => data.seat_id === seat.id);
            if (seatDataEntry) {
                if (seatDataEntry.status === 'sold') {
                    seat.classList.add('sold');
                }
            }
        });
        updateSelectedCount(); // Update counts after updating seat images
    }

    // Function to update selected seat count and total price
    function updateSelectedCount() {
        const selectedSeats = document.querySelectorAll('.row .seat.selected');
        count.textContent = selectedSeats.length;
        const totalPrice = selectedSeats.length * 20; // Assuming each seat costs RM 20
        total.textContent = `RM ${totalPrice.toFixed(2)}`;

        nextButton.disabled = selectedSeats.length === 0;
    }

    // Function to update seat IDs
    function updateSeatIDs(movie_id, location, date, time) {
        const rows = seatingPlan.querySelectorAll('.row');
        rows.forEach(row => {
            const seatsInRow = row.querySelectorAll('.seat');
            seatsInRow.forEach(seat => {
                const originalId = seat.id.split('-')[0]; // Get original seat ID (e.g., A1, B2)
                const newId = `${originalId}-${movie_id}-${location}-${date}-${time}`;
                console.log('Updated Seat ID:', newId);
                seat.id = newId;
            });
        });
    }

    // Function to handle booking process on next button click
    nextButton.addEventListener('click', function() {
        const selectedSeats = document.querySelectorAll('.row .seat.selected');
        const seatNumbers = [...selectedSeats].map(seat => seat.id);
        const selectedLocation = locationSelect.value;
        const selectedDate = dateInput.value;
        const selectedTime = timeSelect.value;
        const totalAmount = total.textContent;

        const bookingDetails = {
            movie_id: movie_id,
            movie_title: movie_title,
            seats: seatNumbers,
            location: selectedLocation,
            date: selectedDate,
            time: selectedTime,
            total: totalAmount 
        };

        console.log('Booking Details:', bookingDetails);

        // Store booking details in session storage
        sessionStorage.setItem('bookingDetails', JSON.stringify(bookingDetails));

        // Redirect to payment info page
        window.location.href = 'payment_info.php';
    });

    // Function to handle going back and clearing selected seats
    function goBack() {
        const bookingDetails = JSON.parse(sessionStorage.getItem('bookingDetails'));
        if (bookingDetails) {
            const { seats } = bookingDetails;
            seats.forEach(seat_id => {
                const seatElement = document.getElementById(seat_id);
                if (seatElement) {
                    seatElement.classList.remove('selected');
                    seatElement.classList.add('available');
                }
            });
        }

        sessionStorage.removeItem('bookingDetails');
        window.location.href = '/homepage';
    }

    // Event listener for go back button
    document.getElementById('goBack').addEventListener('click', goBack);
});
