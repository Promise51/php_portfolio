<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['receipt'])) {
    header('Location: dashboard.php');
    exit();
}
$receipt = $_SESSION['receipt'];
include 'db.php';
$user_id = $_SESSION['user_id'];
$email = '';
$sql = "SELECT email FROM users WHERE id = $user_id LIMIT 1";
$result = $conn->query($sql);
if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $email = $row['email'];
}

// Handle AJAX email send
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email']) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
    require_once __DIR__ . '/PHPMailer/src/SMTP.php';
    require_once __DIR__ . '/PHPMailer/src/Exception.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_gmail@gmail.com'; // Your Gmail address
    $mail->Password = 'your_app_password'; // App password, not your Gmail password!
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('your_gmail@gmail.com', 'Chidoxtest');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Your Flight Ticket Receipt';
    $mail->Body = "<h2>Flight Booking Receipt</h2>"
        . "<table border='1' cellpadding='8' style='border-collapse:collapse;'>"
        . "<tr><th>Passenger</th><td>" . htmlspecialchars($_SESSION['username']) . "</td></tr>"
        . "<tr><th>Flight Number</th><td>" . htmlspecialchars($receipt['flight_number']) . "</td></tr>"
        . "<tr><th>Destination</th><td>" . htmlspecialchars($receipt['destination']) . "</td></tr>"
        . "<tr><th>Date</th><td>" . htmlspecialchars($receipt['date']) . "</td></tr>"
        . "<tr><th>Booking ID</th><td>" . htmlspecialchars($receipt['booking_id']) . "</td></tr>"
        . "</table>";
    if ($mail->send()) {
        echo '<div class="success">Receipt sent to your email!</div>';
        unset($_SESSION['receipt']);
    } else {
        echo '<div class="error">Failed to send email. Error: ' . $mail->ErrorInfo . '</div>';
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Receipt</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container" id="receipt-area">
        <h2>Flight Booking Receipt</h2>
        <table class="receipt-table">
            <tr><th>Passenger</th><td><?php echo htmlspecialchars($_SESSION['username']); ?></td></tr>
            <tr><th>Flight Number</th><td><?php echo htmlspecialchars($receipt['flight_number']); ?></td></tr>
            <tr><th>Destination</th><td><?php echo htmlspecialchars($receipt['destination']); ?></td></tr>
            <tr><th>Date</th><td><?php echo htmlspecialchars($receipt['date']); ?></td></tr>
            <tr><th>Booking ID</th><td><?php echo htmlspecialchars($receipt['booking_id']); ?></td></tr>
        </table>
        <form id="emailForm" method="POST" style="display:inline;" onsubmit="return false;">
            <input type="hidden" name="send_email" value="1">
            <button type="button" class="logout" style="margin-right:10px;" onclick="sendEmailReceipt()">Send Receipt to Email</button>
        </form>
        <button onclick="printReceiptOnly()" class="logout" style="margin-right:10px;">Print Receipt</button>
        <a href="dashboard.php" class="logout">Back to Dashboard</a>
    </div>
<script>
function printReceiptOnly() {
    var printContents = document.getElementById('receipt-area').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}

function sendEmailReceipt() {
    var btn = document.querySelector('#emailForm button');
    btn.disabled = true;
    btn.innerText = 'Sending...';
    var xhr = new XMLHttpRequest();
    xhr.open('POST', window.location.href, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            btn.disabled = false;
            btn.innerText = 'Send Receipt to Email';
            var msg = document.getElementById('email-msg');
            if (!msg) {
                msg = document.createElement('div');
                msg.id = 'email-msg';
                btn.parentNode.insertBefore(msg, btn.nextSibling);
            }
            msg.innerHTML = xhr.responseText;
        }
    };
    xhr.send('send_email=1');
}
</script>
</body>
</html>
