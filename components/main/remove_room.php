<?php
// Include the database connection file
require("dbconnect.php");

// Check if the form has been submitted and the room ID is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_room"]) && isset($_POST["room_id"])) {
    // Retrieve the room ID from the form data
    $room_id = $_POST["room_id"];

    // Prepare a delete statement to remove the room from the bookings table
    $delete_booking_stmt = $conn->prepare("DELETE FROM bookings WHERE room_id = ?");
    $delete_booking_stmt->bind_param("i", $room_id);

    // Execute the delete statement for bookings
    if ($delete_booking_stmt->execute()) {
        // Prepare a delete statement to remove the room from the rooms table
        $delete_room_stmt = $conn->prepare("DELETE FROM rooms WHERE room_id = ?");
        $delete_room_stmt->bind_param("i", $room_id);

        // Execute the delete statement for rooms
        if ($delete_room_stmt->execute()) {
            // Room and associated bookings deleted successfully
            echo "Room and associated bookings deleted successfully.";
        } else {
            // Error occurred while deleting the room
            echo "Error: Unable to delete the room.";
        }

        // Close the delete room statement
        $delete_room_stmt->close();
    } else {
        // Error occurred while deleting the bookings
        echo "Error: Unable to delete the bookings.";
    }

    // Close the delete bookings statement
    $delete_booking_stmt->close();
}

// Close the database connection
$conn->close();
?>

