<?php
// Include the database connection file
require("dbconnect.php");

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_booking"])) {
    // Redirect to confirmation page with the selected room ID
    if (isset($_POST["room_id"])) {
        header("Location: book_room.php?room_id=" . $_POST["room_id"]);
        exit();
    }
}

// Query to select available rooms with hotel information
$sql = "SELECT rooms.room_id, rooms.room_type, rooms.price_per_night, hotels.name AS hotel_name 
        FROM rooms 
        INNER JOIN hotels ON rooms.hotel_id = hotels.hotel_id 
        WHERE rooms.availability_status = 'available'";
$result = $conn->query($sql);

// Check if there are any available rooms
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<p>Room ID: " . $row["room_id"]. "</p>";
        echo "<p>Hotel Name: " . $row["hotel_name"]. "</p>";
        echo "<p>Type: " . $row["room_type"]. "</p>";
        echo "<p>Price: $" . $row["price_per_night"]. "</p>";
        // Add hidden input field for room ID
        echo "<form action='' method='post'>";
        echo "<input type='hidden' name='room_id' value='" . $row["room_id"] . "'>";
        // Submit button
        echo "<button type='submit' name='submit_booking'>Book Room</button>";
        echo "</form>";
        echo "</div>";
    }
} else {
    echo "No available rooms";
}

// Close the database connection
$conn->close();
?>



