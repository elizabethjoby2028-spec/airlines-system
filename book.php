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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmed</title>
    <style>
        body {font-family: Arial; text-align:center; margin-top:50px; background:#eef;}
        h2 {color: green;}
    </style>
    <script>
        // Redirect to homepage (relative path)
        setTimeout(function(){
            window.location.href = "index_test.html";
        }, 3000);
    </script>
</head>
<body>
    <h2>Booking Confirmed!</h2>
    <p>You will be redirected to the homepage in 3 seconds...</p>
    <p>If not, click <a href="index_test.html">here</a>.</p>
</body>
</html>
