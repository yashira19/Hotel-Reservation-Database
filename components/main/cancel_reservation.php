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

// Check if the booking ID is provided in the POST request
if (!isset($_POST["booking_id"]) || empty($_POST["booking_id"])) {
    header("Location: view_reservations.php?error=booking_id_missing");
    exit();
}

// Retrieve the booking ID from the POST request
$booking_id = $_POST["booking_id"];

// Query to select booking details based on the booking ID
$stmt = $conn->prepare("SELECT * FROM bookings WHERE booking_id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the booking exists
if ($result->num_rows == 0) {
    header("Location: view_reservations.php?error=booking_not_found");
    exit();
}

// Fetch booking details
$row = $result->fetch_assoc();
$room_id = $row["room_id"];

// Delete the booking from the bookings table
$delete_stmt = $conn->prepare("DELETE FROM bookings WHERE booking_id = ?");
$delete_stmt->bind_param("i", $booking_id);
$delete_stmt->execute();
$delete_stmt->close();

// Update room availability status to "available"
$update_stmt = $conn->prepare("UPDATE rooms SET availability_status = 'available' WHERE room_id = ?");
$update_stmt->bind_param("i", $room_id);
$update_stmt->execute();
$update_stmt->close();

// Redirect back to the view reservations page with a success message
header("Location: view_reservations.php?success=booking_cancelled");
exit();
?>
