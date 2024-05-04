<?php
// Start session to access session variables
session_start();

// Include the database connection file
require("dbconnect.php");

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Retrieve the username from the session
$username = $_SESSION["username"];

// Query to select bookings for the logged-in user
$stmt = $conn->prepare("SELECT * FROM bookings WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any bookings
if ($result->num_rows > 0) {
    // Display booking information
    while ($row = $result->fetch_assoc()) {
        echo "<p>Booking ID: " . $row["booking_id"] . "</p>";
        echo "<p>Room ID: " . $row["room_id"] . "</p>";
        echo "<p>Start Date: " . $row["start_date"] . "</p>";
        echo "<p>End Date: " . $row["end_date"] . "</p>";
        // Button to cancel reservation
        echo "<form action='cancel_reservation.php' method='post'>";
        echo "<input type='hidden' name='booking_id' value='" . $row["booking_id"] . "'>";
        echo "<button type='submit' name='cancel_booking'>Cancel Reservation</button>";
        echo "</form>";
        echo "<hr>";
    }
} else {
    echo "No reservations found.";
}

// Close prepared statement
$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservations</title>
</head>
<body>
    <a href="home_page.php">Back to Home</a>
</body>
</html>
