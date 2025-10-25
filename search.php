<?php
include 'db.php';

$s = isset($_GET['source']) ? $_GET['source'] : '';
$d = isset($_GET['destination']) ? $_GET['destination'] : '';

// Query flights (case-insensitive)
$q = mysqli_query($conn, "SELECT * FROM flights WHERE LOWER(source)=LOWER('$s') AND LOWER(destination)=LOWER('$d')");

if(mysqli_num_rows($q) > 0){
    echo "<div class='flights-container'>";
    while($r = mysqli_fetch_assoc($q)){
        echo "<div class='flight'>";
        
        // Flight info
        echo "<div class='flight-info'>";
        echo "<span><strong>Flight No:</strong> ".$r['flight_no']."</span>";
        echo "<span><strong>Airline:</strong> ".$r['airline_name']."</span>";
        echo "<span><strong>Type:</strong> ".$r['flight_type']."</span>";
        echo "<span><strong>From:</strong> ".$r['source']."</span>";
        echo "<span><strong>To:</strong> ".$r['destination']."</span>";
        echo "<span><strong>Departure:</strong> ".$r['departure_time']."</span>";
        echo "<span><strong>Arrival:</strong> ".$r['arrival_time']."</span>";
        echo "<span><strong>Seats:</strong> ".$r['seats_available']."</span>";
        echo "<span><strong>Price:</strong> ₹".$r['price']."</span>";
        echo "</div>";

        // Booking form
        echo "<form action='book.php' method='POST' class='booking-form'>";
        echo "<input type='hidden' name='flight_id' value='".$r['flight_id']."'>";
        echo "<input type='text' name='name' placeholder='Name' required>";
        echo "<input type='email' name='email' placeholder='Email' required>";
        echo "<input type='text' name='phone' placeholder='Phone' required>";
        echo "<input type='text' name='seat_no' placeholder='Seat No' required>";
        echo "<button type='submit'>Book</button>";
        echo "</form>";

        echo "</div>"; // end flight
    }
    echo "</div>"; // end flights-container
}else{
    echo "<p style='text-align:center; font-weight:bold; color:red;'>No flights found from '".$s."' to '".$d."'.</p>";
}
?>
