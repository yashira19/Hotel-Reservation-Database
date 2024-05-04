<?php
session_start(); 

if (!isset($_SESSION["firstName"])) {
    header("Location: login.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Home Page</title>
</head>
<body>
  <h1>Welcome to the Hotel Reservation System</h1>
  <p>Please select an option:</p>
  <ul>
    <li><a href="available_rooms.php">Book a Room</a></li>
    <li><a href="view_reservations.php">View Reservations</a></li>
    <li><a href="cancel_reservation.php">Cancel Reservation</a></li>
    <li><a href="index.php">Log out</a></li>

  </ul>
</body>
</html>
