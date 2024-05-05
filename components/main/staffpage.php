<?php
session_start(); // Start the session to access session variables
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Page</title>
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
        <?php 
        // Include the database connection file
        require("dbconnect.php");

        // Query to select available rooms
        $sql = "SELECT * FROM rooms WHERE availability_status = 'available'";
        $result = $conn->query($sql);

        // Check if there are any available rooms
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<div>";
                echo "<p>Room ID: " . $row["room_id"]. "</p>";
                echo "<p>Type: " . $row["room_type"]. "</p>";
                echo "<p>Price: $" . $row["price_per_night"]. "</p>";
                // Add a button/link to remove the room
                echo "<form action='remove_room.php' method='post'>";
                echo "<input type='hidden' name='room_id' value='" . $row["room_id"] . "'>";
                echo "<button type='submit' name='remove_room'>Remove Room</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "No available rooms";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>

    <h2>Add New Room</h2>
    <form action="add_room.php" method="post">
        <label for="room_type">Room Type:</label>
        <select id="room_type" name="room_type" required>
            <option value="Standard">Standard</option>
            <option value="Queen">Queen</option>
            <option value="Suite">Suite</option>
        </select><br>
        <label for="price_per_night">Price Per Night:</label>
        <input type="number" id="price_per_night" name="price_per_night" required><br>
        <label for="hotel_name">Hotel Name:</label>
        <input type="text" id="hotel_name" name="hotel_name" required><br>
        <!-- Add more fields for other room details -->
        <button type="submit" name="add_room">Add Room</button>
    </form>
</body>
</html>


