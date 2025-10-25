<?php
include 'db.php';

// Join bookings, passengers, and flights to get full info
$q = mysqli_query($conn, "
    SELECT b.booking_id, p.name, p.email, p.phone, f.flight_no, f.airline_name, f.source, f.destination, f.departure_time, f.arrival_time, b.seat_no, f.price
    FROM bookings b
    JOIN passengers p ON b.passenger_id = p.passenger_id
    JOIN flights f ON b.flight_id = f.flight_id
    ORDER BY b.booking_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booked Flights</title>
    <style>
        body {font-family: Arial; background:#eef; padding:20px;}
        h2 {text-align:center; color:navy;}
        table {width:100%; border-collapse: collapse; margin-top:20px; background:white;}
        th, td {padding:10px; border:1px solid #ccc; text-align:center;}
        th {background-color: navy; color:white;}
        tr:nth-child(even) {background-color:#f2f2f2;}
        a {color:navy; text-decoration:none;}
        a:hover {text-decoration:underline;}
    </style>
</head>
<body>
    <h2>All Booked Flights</h2>
    <a href="index.html">Back to Home</a>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>Passenger Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Flight No</th>
            <th>Airline</th>
            <th>From</th>
            <th>To</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>Seat No</th>
            <th>Price (₹)</th>
        </tr>
        <?php
        if(mysqli_num_rows($q) > 0){
            while($r = mysqli_fetch_assoc($q)){
                echo "<tr>";
                echo "<td>".$r['booking_id']."</td>";
                echo "<td>".$r['name']."</td>";
                echo "<td>".$r['email']."</td>";
                echo "<td>".$r['phone']."</td>";
                echo "<td>".$r['flight_no']."</td>";
                echo "<td>".$r['airline_name']."</td>";
                echo "<td>".$r['source']."</td>";
                echo "<td>".$r['destination']."</td>";
                echo "<td>".$r['departure_time']."</td>";
                echo "<td>".$r['arrival_time']."</td>";
                echo "<td>".$r['seat_no']."</td>";
                echo "<td>".$r['price']."</td>";
                echo "</tr>";
            }
        }else{
            echo "<tr><td colspan='12'>No bookings yet.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
