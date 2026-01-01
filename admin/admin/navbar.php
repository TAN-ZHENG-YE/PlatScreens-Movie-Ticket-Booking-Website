<style>
	.admin-nav-item {
		padding: 10px 20px;
		margin-bottom: 5px;
		display: block;
		text-decoration: none;
		color: #333;
		background-color: white;
		transition: background-color 0.3s;
	}

	.admin-nav-item:hover {
		background-color: #007bff;
		color: white;
	}

	.admin-nav-item.active {
		background-color: #007bff !important;
		color: white !important;
	}

	.icon-field {
		margin-right: 10px;
	}
</style>

<nav id="sidebar" class='mx-lt-5 bg-light' style="background-color: #e3f2fd !important;">
	<div class="container-fluid">
		<div class="sidebar-list">
			<a href="index.php?page=book" class="admin-nav-item nav-book active"><span class='icon-field'><i class="fa fa-ticket"></i></span>Booking History</a>
			<a href="index.php?page=movielist" class="admin-nav-item nav-movielist"><span class='icon-field'><i class="fa fa-list"></i></span> Movie List</a>
		</div>
	</div>
</nav>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const menuItems = document.querySelectorAll('.admin-nav-item');

		// Set default active item to Booking History
		menuItems.forEach(item => {
			if (item.classList.contains('nav-book')) {
				item.classList.add('active');
				item.style.backgroundColor = '#007bff'; // Set background color for Booking History
			}
		});

		menuItems.forEach(item => {
			item.addEventListener('click', function() {
				// Remove active class from all items
				menuItems.forEach(i => {
					i.classList.remove('active');
					i.style.backgroundColor = 'white'; // Reset background color
				});
				// Add active class to the clicked item
				this.classList.add('active');
				this.style.backgroundColor = '#007bff'; // Set background color for active item
			});
		});

	});
</script>