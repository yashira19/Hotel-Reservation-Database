<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
</head>
<body>
    <?php
    session_start(); // Start the session to access session variables

    // Include the database connection file
    require("dbconnect.php");

    // Check if the room ID is provided in the URL
    if (!isset($_GET["room_id"]) || empty($_GET["room_id"])) {
        // Redirect back to the homepage or display an error message
        header("Location: homepage.php?error=room_id_missing");
        exit();
    }

    // Retrieve the room ID from the URL parameter
    $room_id = $_GET["room_id"];

    // Query to select room details based on the room ID
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the room exists
    if ($result->num_rows == 0) {
        // Redirect back to the homepage or display an error message
        header("Location: homepage.php?error=room_not_found");
        exit();
    }

    // Fetch room details
    $row = $result->fetch_assoc();
    $room_type = $row["room_type"];
    $price_per_night = $row["price_per_night"];

    // Close the prepared statement
    $stmt->close();

    // Handle form submission for booking confirmation
    $successMessage = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_booking"])) {
        // Retrieve the booking start date and end date from the form
        $start_date = $_POST["start_date"]; // You need to add validation for the start date
        $end_date = $_POST["end_date"]; // You need to add validation for the end date

        // Check if the username is set in the session
        if(isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
        } else {
            // Redirect back to the login page if username is not set
            header("Location: ../home_page.php");
            exit();
        }

        // Validate if end date is not before start date
        if ($start_date > $end_date) {
            echo "<p>End date cannot be before the start date.</p>";
        } else {
            // Insert booking details into the database
            $insert_stmt = $conn->prepare("INSERT INTO bookings (room_id, username, start_date, end_date) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param("isss", $room_id, $username, $start_date, $end_date);
            if ($insert_stmt->execute()) {
                $successMessage = "Booking successful!";
                // Update room availability status to "booked"
                $update_stmt = $conn->prepare("UPDATE rooms SET availability_status = 'booked' WHERE room_id = ?");
                $update_stmt->bind_param("i", $room_id);
                $update_stmt->execute();
                $update_stmt->close();
            } else {
                $successMessage = "Booking failed. Please try again.";
            }
            // Close prepared statements
            $insert_stmt->close();
        }
    }
    ?>

    <h2>Booking Confirmation</h2>
    <?php if (!empty($successMessage)) : ?>
        <p><?php echo $successMessage; ?></p>
        <a href="home_page.php"><button>Back to Home</button></a>
    <?php else : ?>
        <p>Confirm Booking for Room <?php echo $room_id; ?></p>
        <p>Room Type: <?php echo $room_type; ?></p>
        <p>Price Per Night: $<?php echo $price_per_night; ?></p>
        <form action="" method="post" onsubmit="return validateDates()">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>
            <button type="submit" name="confirm_booking">Confirm Booking</button>
        </form>
        <script>
            function validateDates() {
                var startDate = new Date(document.getElementById("start_date").value);
                var endDate = new Date(document.getElementById("end_date").value);
                if (endDate < startDate) {
                    alert("End date cannot be before the start date.");
                    return false;
                }
                return true;
            }
        </script>
    <?php endif; ?>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>




