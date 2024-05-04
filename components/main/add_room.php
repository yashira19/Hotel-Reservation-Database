<?php
require("dbconnect.php");

// Retrieve form data
$roomType = $_POST["room_type"];
$pricePerNight = $_POST["price_per_night"];
$hotelName = $_POST["hotel_name"]; // Assuming you have a form field for hotel name

// Query to retrieve hotel_id based on hotel name
$queryHotelId = $conn->prepare("SELECT hotel_id FROM hotels WHERE name = ?");
$queryHotelId->bind_param("s", $hotelName);
$queryHotelId->execute();
$resultHotelId = $queryHotelId->get_result();

if ($resultHotelId->num_rows > 0) {
    $rowHotelId = $resultHotelId->fetch_assoc();
    $hotelId = $rowHotelId["hotel_id"];

    // Insert the new room into the rooms table
    $queryInsertRoom = $conn->prepare("INSERT INTO rooms (room_type, price_per_night, hotel_id) VALUES (?, ?, ?)");
    $queryInsertRoom->bind_param("sdi", $roomType, $pricePerNight, $hotelId);
    $queryInsertRoom->execute();
    
    echo "Room added successfully.";
} else {
    echo "Hotel not found.";
}

// Close prepared statements and database connection
$queryHotelId->close();
$queryInsertRoom->close();
$conn->close();
?>

