<?php
session_start(); // Start the session to access session variables
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms</title>
</head>
<body>
    <?php
    // Check if the username is set in the session
    if(isset($_SESSION['username'])) {
        // Display a welcome message with the username
        echo "<h2>Welcome, " . $_SESSION['username'] . "!</h2>";
    } else {
        // If username is not set, display a generic welcome message
        echo "<h2>Welcome!</h2>";
    }
    ?>
    <h2>Available Rooms</h2>
    <div id="available-rooms">
        <?php include 'retrieveavailablerooms.php'; ?>
    </div>
</body>
</html>


