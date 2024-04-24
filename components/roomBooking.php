<?php
// Start the session
session_start();

// Redirect to room booking page if the form wasn't submitted
if (!isset($_POST["submit"])) {
    header("Location: ../room_booking.php");
    exit();
}

// Include database connection
require("dbconnect.php");

// Retrieve user input from form
$room_type = $_POST["room_type"];
$booking_date = $_POST["booking_date"];
$start_time = $_POST["start_time"];
$end_time = $_POST["end_time"];

// Check for empty fields
if (empty($room_type) || empty($booking_date) || empty($start_time) || empty($end_time)) {
    header("Location: ../room_booking.php?error=emptyfields");
    exit();
}

// Validate booking date
// You can add more validation rules as needed
// For example, you can check if the booking date is in the future
$current_date = date("Y-m-d");
if ($booking_date < $current_date) {
    header("Location: ../roomBooking.php?error=invaliddate");
    exit();
}

// Prepare SQL statement to check for existing bookings
$stmt = $conn->prepare("SELECT * FROM RoomBookings WHERE RoomType = ? AND BookingDate = ? AND ((StartTime <= ? AND EndTime > ?) OR (StartTime < ? AND EndTime >= ?))");
$stmt->bind_param("ssssss", $room_type, $booking_date, $start_time, $start_time, $end_time, $end_time);
$stmt->execute();
$result = $stmt->get_result();

// Check if room is already booked for the selected time slot
if ($result->num_rows > 0) {
    // Room is already booked
    header("Location: ../roomBooking.php?error=bookingconflict");
    exit();
}

// Insert booking into database
$stmt2 = $conn->prepare("INSERT INTO RoomBookings (UserID, RoomType, BookingDate, StartTime, EndTime) VALUES (?, ?, ?, ?, ?)");
$stmt2->bind_param("issss", $_SESSION["userID"], $room_type, $booking_date, $start_time, $end_time);
if ($stmt2->execute()) {
    // Booking successful
    header("Location: ../roomBooking.php?success=true");
    exit();
} else {
    // Error occurred during booking
    header("Location: ../roomBooking.php?error=bookingfailed");
    exit();
}

// Close database connection
// $stmt->close();
// $stmt2->close();
// $conn->close();

// Redirect to index page after successful registration
// header("Location: ../components/index.php?error=none");
// exit();
