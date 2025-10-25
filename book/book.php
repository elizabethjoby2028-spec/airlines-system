<?php
include 'db.php';
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$seat = $_POST['seat_no'];
$fid = $_POST['flight_id'];

mysqli_query($conn,"INSERT INTO passengers(name,email,phone) VALUES('$name','$email','$phone')");
$pid = mysqli_insert_id($conn);
mysqli_query($conn,"INSERT INTO bookings(passenger_id,flight_id,seat_no) VALUES($pid,$fid,'$seat')");
mysqli_query($conn,"UPDATE flights SET seats_available=seats_available-1 WHERE flight_id=$fid");

echo "<h2>Booking Confirmed!</h2>";
echo "<a href='index.html'>Back</a>";
?>
