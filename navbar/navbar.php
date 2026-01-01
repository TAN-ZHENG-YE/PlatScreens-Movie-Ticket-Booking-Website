<?php
$userData = getUserSessionData();
$username = $userData['username'];
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message after retrieving it
}
?>

<link rel="stylesheet" href="/navbar/navbar.css">
<div class="navbar">
    <div class="navbar-container">
        <div class="logo-container"><h1 class="logo">PlatScreens</h1></div>
        <div class="menu-container">
            <ul class="menu-list">
                <li class="menu-list-item active"><a href="/homepage/index.php">Home</a></li>
                <li class="menu-list-item"><a href="/about_us/about_us.php">About</a></li>
                <li class="menu-list-item"><a href="/contact_us/CustomerServiceForm.php">Contact</a></li>
                <?php if (!$userData['is_authenticated']): ?>
                    <li class="menu-list-item"><a href="/login/login.php">Sign Up & Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <?php if ($userData['is_authenticated']): ?>
            <div class="profile-container">
                <img class="profile-picture" src="<?php echo $s3_url; ?>/images/killua.jpeg" alt="">
                <div class="profile-text-container">
                    <span class="username"><?php echo htmlspecialchars($userData['username']); ?></span>
                    <span class="profile-text">Profile</span>
                    <i class="fas fa-caret-down dropdown-icon"></i>
                    <div class="dropdown-menu hidden">
                        <div id="editProfile" class="dropdown-item">Edit Profile</div>
                        <div id="changePassword" class="dropdown-item">Change Password</div>
                        <div id="bookingHistory" class="dropdown-item">Booking History</div>
                        <a href="/login/logout.php" class="dropdown-item">Logout</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="dialog" class="dialog">
    <div class="dialog-content">
        <span class="close">&times;</span>
        <h2 class="form-title" id="dialog-title"></h2>
        <div id="dialog-body"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownIcon = document.querySelector('.dropdown-icon');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    const dialog = document.getElementById('dialog');
    const dialogBody = document.getElementById('dialog-body');
    const dialogTitle = document.getElementById('dialog-title');
    const closeBtn = document.querySelector('.close');

    // Toggle dropdown menu visibility when clicking the icon
    dropdownIcon.addEventListener('click', function(event) {
        event.stopPropagation();
        dropdownMenu.classList.toggle('active');
        dropdownIcon.classList.toggle('active');
    });

    // Handle Edit Profile click
    document.getElementById('editProfile').addEventListener('click', function(event) {
        event.preventDefault();
        dialogTitle.textContent = 'Edit Profile'; // Set the title
        
        fetch('/user_profile/edit_profile.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                if (html.trim() === '') {
                    throw new Error('Empty response received');
                }
                dialogBody.innerHTML = html;
                dialog.style.display = 'flex';
                dropdownMenu.classList.remove('active');
            })
            .catch(error => {
                console.error('Error:', error);
                dialogBody.innerHTML = `<div class="error-message">Error loading profile form: ${error.message}</div>`;
                dialog.style.display = 'flex';
            });
    });

    // Handle Change Password click
    document.getElementById('changePassword').addEventListener('click', function(event) {
        event.preventDefault();
        dialogTitle.textContent = 'Change Password'; // Set the title
        
        fetch('/user_profile/change_password.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                if (html.trim() === '') {
                    throw new Error('Empty response received');
                }
                dialogBody.innerHTML = html;
                dialog.style.display = 'flex';
                dropdownMenu.classList.remove('active');
            })
            .catch(error => {
                console.error('Error:', error);
                dialogBody.innerHTML = `<div class="error-message">Error loading password form: ${error.message}</div>`;
                dialog.style.display = 'flex';
            });
    });

    // Handle Booking History click
    document.getElementById('bookingHistory').addEventListener('click', function(event) {
        event.preventDefault();
        dialogTitle.textContent = 'Booking History';
        
        fetch('/view_history/booking_history.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                if (html.trim() === '') {
                    throw new Error('Empty response received');
                }
                dialogBody.innerHTML = html;
                dialog.style.display = 'flex';
                dropdownMenu.classList.remove('active');
            })
            .catch(error => {
                console.error('Error:', error);
                dialogBody.innerHTML = `<div class="error-message">Error loading booking history: ${error.message}</div>`;
                dialog.style.display = 'flex';
            });
    });

    // Close dialog
    closeBtn.addEventListener('click', function() {
        dialog.style.display = 'none';
        dialogBody.innerHTML = '';
        dialogTitle.textContent = ''; // Clear the title
    });

    // Close dialog when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === dialog) {
            dialog.style.display = 'none';
            dialogBody.innerHTML = '';
            dialogTitle.textContent = ''; // Clear the title
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.profile-text-container')) {
            dropdownMenu.classList.remove('active');
            dropdownIcon.classList.remove('active');
        }
    });

    // Show popup message if exists
    <?php if ($message): ?>
        alert(<?php echo json_encode($message); ?>);
    <?php endif; ?>
});
</script>