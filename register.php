<?php
// Database connection parameters
$host = "localhost";
$username = ""; // Change this to your MySQL username
$password = ""; // Change this to your MySQL password
$database = ""; // Change this to your MySQL database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // SQL query to insert user into database
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

    // Execute query
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>