<style>
	td img{
		width: 50px;
		height: 75px;
		margin:auto;
	}
	td p {
		margin: 0;
	}
</style>
<?php
require '../../global.php';
$conn = getDatabaseConnection();
?>
<div class="container-fluid">
	<div class="row">
		<div class="card col-md-12 mt-3">
			<div class="card-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Invoice ID</th>
							<th class="text-center">Name</th>
							<th class="text-center">Phone</th>
							<th class="text-center">Email</th>
							<th class="text-center">Chosen Movie</th>
							<th class="text-center">Chosen Cinema Location</th>
							<th class="text-center">Chosen Show Date</th>
							<th class="text-center">Chosen Show Time</th>
							<th class="text-center">Chosen Seat Number</th>
							<th class="text-center">Total Amount Paid</th>
							<th class="text-center">Payment Method</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$booking = $conn->query("SELECT * FROM booking_info");
						while($row = $booking->fetch_assoc()){
						?>
						 <tr>
						 	<td><?php echo $i++; ?></td>
						 	<td><?php echo $row['invoice_id']; ?></td>
						 	<td><?php echo $row['userName']; ?></td>
						 	<td><?php echo $row['userPhone']; ?></td>
						 	<td><?php echo $row['userEmail']; ?></td>
						 	<td><?php echo $row['movie_title']; ?></td>
						 	<td><?php echo $row['chosen_location']; ?></td>
						 	<td><?php echo date("M d, Y", strtotime($row['showDate'])); ?></td>
						 	<td><?php echo date("h:i A", strtotime($row['showTime'])); ?></td>
						 	<td><?php echo $row['seat_number']; ?></td>
						 	<td><?php echo $row['total_amount']; ?></td>
						 	<td><?php echo $row['payment_method']; ?></td>
						 </tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


