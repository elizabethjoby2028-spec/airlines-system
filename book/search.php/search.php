<?php
include 'db.php';
$s = $_GET['source'];
$d = $_GET['destination'];
$q = mysqli_query($conn,"SELECT * FROM flights WHERE LOWER(source)=LOWER('$s') AND LOWER(destination)=LOWER('$d')");

if(mysqli_num_rows($q) > 0){
    while($r = mysqli_fetch_assoc($q)){
        echo "<div class='flight'>";
        echo "Flight ID: ".$r['flight_id']." | Flight No: ".$r['flight_no']." | Airline: ".$r['airline_name']." | Type: ".$r['flight_type']."<br>";
        echo "From: ".$r['source']." → To: ".$r['destination']."<br>";
        echo "Departure: ".$r['departure_time']." | Arrival: ".$r['arrival_time']."<br>";
        echo "Seats Available: ".$r['seats_available']." | Price: ₹".$r['price']."<br>";
        echo "<form action='book.php' method='POST'>";
        echo "<input type='hidden' name='flight_id' value='".$r['flight_id']."'>";
        echo "<input type='text' name='name' placeholder='Name'>";
        echo "<input type='email' name='email' placeholder='Email'>";
        echo "<input type='text' name='phone' placeholder='Phone'>";
        echo "<input type='text' name='seat_no' placeholder='Seat No'>";
        echo "<button type='submit'>Book</button>";
        echo "</form></div><hr>";
    }
}else{
    echo "No flights found for '".$s."' → '".$d."'.";
}
?>
