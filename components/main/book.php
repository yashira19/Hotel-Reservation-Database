<?php
session_start(); 

if (!isset($_SESSION["firstName"])) {
    header("Location: login.php"); 
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    require("dbconnect.php");

    // Retrieve user input from the form
    $booking_date = $_POST["start_date"];
    $room_id = $_POST["room_id"];

    // Calculate the end date (e.g., add one day to the start date for simplicity)
    $end_date = date('Y-m-d', strtotime($booking_date . ' + 1 day'));

    // Insert booking into the database
    $stmt = $conn->prepare("INSERT INTO bookings (room_id, username, start_date, end_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iss", $room_id, $_SESSION["username"], $booking_date, $end_date);
    $stmt->execute();

    // Update room availability status to "booked"
    $update_stmt = $conn->prepare("UPDATE rooms SET availability_status = 'booked' WHERE room_id = ?");
    $update_stmt->bind_param("i", $room_id);
    $update_stmt->execute();

    // Close prepared statements
    $stmt->close();
    $update_stmt->close();

    // Close the database connection
    $conn->close();

    // Redirect back to the homepage or display a confirmation message
    header("Location: homepage.php?success=booking_successful");
    exit();
}
?>

