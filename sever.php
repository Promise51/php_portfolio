<?php
session_start();
include 'db.php';

// Signup
if (isset($_POST['signup'])) {
	$username = $conn->real_escape_string($_POST['username']);
	$email = $conn->real_escape_string($_POST['email']);
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
	if ($conn->query($sql) === TRUE) {
		header('Location: sign_in.php?signup=success');
		exit();
	} else {
		header('Location: sign_up.php?error=exists');
		exit();
	}
}

// Signin
if (isset($_POST['signin'])) {
	$username = $conn->real_escape_string($_POST['username']);
	$password = $_POST['password'];
	$sql = "SELECT * FROM users WHERE username='$username'";
	$result = $conn->query($sql);
	if ($result && $result->num_rows === 1) {
		$user = $result->fetch_assoc();
		if (password_verify($password, $user['password'])) {
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			header('Location: dashboard.php');
			exit();
		}
	}
	header('Location: sign_in.php?error=invalid');
	exit();
}

// Book flight
if (isset($_POST['book_flight']) && isset($_SESSION['user_id'])) {
	$flight_number = $conn->real_escape_string($_POST['flight_number']);
	$destination = $conn->real_escape_string($_POST['destination']);
	$date = $conn->real_escape_string($_POST['date']);
	$user_id = $_SESSION['user_id'];
	$sql = "INSERT INTO bookings (user_id, flight_number, destination, date) VALUES ($user_id, '$flight_number', '$destination', '$date')";
	if ($conn->query($sql) === TRUE) {
		$booking_id = $conn->insert_id;
		$_SESSION['receipt'] = [
			'flight_number' => $flight_number,
			'destination' => $destination,
			'date' => $date,
			'booking_id' => $booking_id
		];
		header('Location: receipt.php');
		exit();
	} else {
		header('Location: dashboard.php?book=fail');
		exit();
	}
}

// Logout
if (isset($_GET['logout'])) {
	session_destroy();
	header('Location: sign_in.php');
	exit();
}

?>
