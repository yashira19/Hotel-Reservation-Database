<!DOCTYPE html>
<html>
<head>
  <title>Book a Room</title>
</head>
<body>
  <h1>Book a Room</h1>
  <form action="confirmation.php" method="post">
    <label for="room_type">Room Type:</label>
    <select id="room_type" name="room_type">
      <option value="full">Full</option>
      <option value="queen">Queen</option>
      <option value="cali_king">Cali King</option>
    </select><br>
    <label for="check_in_date">Check-in Date:</label>
    <input type="date" id="check_in_date" name="check_in_date" required><br>
    <label for="check_out_date">Check-out Date:</label>
    <input type="date" id="check_out_date" name="check_out_date" required><br>
    <input type="submit" value="Book">
  </form>
  <?php if (isset($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>
</body>
</html>