<?php
session_start();
if (!isset($_SESSION['user_id'])) {
	header('Location: sign_in.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Dashboard</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="dashboard-container">
		<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
		<a href="sever.php?logout=1" class="logout">Logout</a>
		<h3>Book a Flight Ticket</h3>
		<?php if (isset($_GET['book']) && $_GET['book'] === 'success'): ?>
			<div class="success">Flight booked successfully!</div>
		<?php elseif (isset($_GET['book']) && $_GET['book'] === 'fail'): ?>
			<div class="error">Failed to book flight. Try again.</div>
		<?php endif; ?>
		<form action="sever.php" method="POST" class="booking-form">
			<input type="text" name="flight_number" placeholder="Flight Number" required>
			<input type="text" name="destination" placeholder="Destination" required>
			<input type="date" name="date" required>
			<button type="submit" name="book_flight">Book Flight</button>
		</form>
	</div>
</body>
</html>
