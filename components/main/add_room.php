<?php
require("dbconnect.php");

// Retrieve form data
$roomType = $_POST["room_type"];
$pricePerNight = $_POST["price_per_night"];
$hotelName = $_POST["hotel_name"]; // Assuming you have a form field for hotel name

// Validate price format
if (!preg_match('/^\d{1,6}(\.\d{1,2})?$/', $pricePerNight)) {
    echo "Invalid price format.";
    exit();
}

// Query to retrieve hotel_id based on hotel name
$queryHotelId = $conn->prepare("SELECT hotel_id FROM hotels WHERE name = ?");
$queryHotelId->bind_param("s", $hotelName);
$queryHotelId->execute();
$resultHotelId = $queryHotelId->get_result();

if ($resultHotelId->num_rows > 0) {
    $rowHotelId = $resultHotelId->fetch_assoc();
    $hotelId = $rowHotelId["hotel_id"];

    // Check if the hotel ID is within a valid range
    if ($hotelId <= 0) {
        echo "Invalid hotel ID.";
    } else {
        // Insert the new room into the rooms table
        $queryInsertRoom = $conn->prepare("INSERT INTO rooms (room_type, price_per_night, hotel_id) VALUES (?, ?, ?)");
        $queryInsertRoom->bind_param("sdi", $roomType, $pricePerNight, $hotelId);
        $queryInsertRoom->execute();

        echo "Room added successfully.";

        // Close prepared statement for inserting room
        $queryInsertRoom->close();
    }
} else {
    echo "Hotel not found.";
}

// Close prepared statement for retrieving hotel ID and database connection
$queryHotelId->close();
$conn->close();
?>


