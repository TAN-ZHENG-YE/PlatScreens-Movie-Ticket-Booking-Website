document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editProfileForm');
    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            console.log('Update profile initiated...');

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Server response:', data);
                if (data.success) {
                    alert('Profile updated successfully');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('An error occurred: ' + error.message);
            });
        });
    } else {
        console.error('Form not found in DOM');
    }
});