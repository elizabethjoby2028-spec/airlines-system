<?php
session_start();
include 'db.php';

// Initialize message variable
if(!isset($_SESSION['message'])){
    $_SESSION['message'] = '';
}

// REGISTER
if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $_SESSION['message'] = "Email already registered!";
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        mysqli_query($conn,"INSERT INTO users(name,email,password) VALUES('$name','$email','$password')");
        $_SESSION['user_id'] = mysqli_insert_id($conn);
        $_SESSION['name'] = $name;
        $_SESSION['message'] = "Registration successful!";
        header("Location: index.php"); exit();
    }
}

// LOGIN
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $q = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($q) == 1){
        $user = mysqli_fetch_assoc($q);
        if(password_verify($password,$user['password'])){
            $_SESSION['user_id']=$user['user_id'];
            $_SESSION['name']=$user['name'];
            $_SESSION['message'] = "Login successful!";
            header("Location: index.php"); exit();
        } else {
            $_SESSION['message'] = "Incorrect password!";
        }
    } else {
        $_SESSION['message'] = "Email not found!";
    }
}

// BOOKING
if(isset($_POST['book']) && isset($_SESSION['user_id'])){
    $flight_id = $_POST['flight_id'];
    $seat_no = $_POST['seat_no'];
    $flight_q = mysqli_query($conn,"SELECT seats_available FROM flights WHERE flight_id=$flight_id");
    $flight = mysqli_fetch_assoc($flight_q);
    if($flight['seats_available'] <= 0){
        $_SESSION['message'] = "No seats available on this flight!";
    } else {
        $uid = $_SESSION['user_id'];
        mysqli_query($conn,"INSERT INTO bookings(passenger_id,flight_id,seat_no) VALUES($uid,$flight_id,'$seat_no')");
        mysqli_query($conn,"UPDATE flights SET seats_available=seats_available-1 WHERE flight_id=$flight_id");
        $_SESSION['message'] = "Booking successful!";
    }
    header("Location: index.php"); exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Airline Booking System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
if(isset($_SESSION['message']) && $_SESSION['message'] != ''){
    echo "<script>alert('".$_SESSION['message']."');</script>";
    $_SESSION['message'] = '';
}
?>

<!-- HEADER -->
<header>
    <div class="header-container">
        <h1>Airline Booking System</h1>
        <nav>
            <a href="#login-section">Login/Register</a>
            <a href="#available-flights">Available Flights</a>
            <a href="#my-bookings">My Bookings</a>
        </nav>
    </div>
</header>

<!-- LOGIN / REGISTER -->
<div id="login-section" style="<?php echo isset($_SESSION['user_id']) ? 'display:none;' : 'display:block;'; ?>">
    <h2>Login</h2>
    <form method="POST" id="login-form">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <p>Don't have an account? <a href="#" onclick="showRegister()">Register here</a></p>
</div>

<div id="register-section" style="display:none;">
    <h2>Register</h2>
    <form method="POST" id="register-form">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <p>Already have an account? <a href="#" onclick="showLogin()">Login here</a></p>
</div>

<!-- DASHBOARD -->
<div id="dashboard-section" style="<?php echo isset($_SESSION['user_id']) ? 'display:block;' : 'display:none;'; ?>">
    <p>Welcome, <?php echo $_SESSION['name'] ?? ''; ?> | <a href="logout.php">Logout</a></p>

    <!-- AVAILABLE FLIGHTS -->
    <h2 id="available-flights">Available Flights</h2>
    <div id="flights-container">
        <?php
        if(isset($_SESSION['user_id'])) {
            $q = mysqli_query($conn, "SELECT * FROM flights WHERE seats_available > 0 ORDER BY departure_time ASC");
            if(mysqli_num_rows($q) > 0){
                while($r = mysqli_fetch_assoc($q)){
                    echo "<div class='flight'>";
                    echo "<div class='flight-info'>";
                    echo "<span><strong>Flight No:</strong> ".$r['flight_no']."</span>";
                    echo "<span><strong>Airline:</strong> ".$r['airline_name']."</span>";
                    echo "<span><strong>From:</strong> ".$r['source']."</span>";
                    echo "<span><strong>To:</strong> ".$r['destination']."</span>";
                    echo "<span><strong>Departure:</strong> ".$r['departure_time']."</span>";
                    echo "<span><strong>Arrival:</strong> ".$r['arrival_time']."</span>";
                    echo "<span><strong>Seats Available:</strong> ".$r['seats_available']."</span>";
                    echo "<span><strong>Price:</strong> ₹".$r['price']."</span>";
                    echo "</div>";
                    echo "<form action='index.php' method='POST' class='booking-form'>";
                    echo "<input type='hidden' name='flight_id' value='".$r['flight_id']."'>";
                    echo "<input type='text' name='seat_no' placeholder='Seat No' required>";
                    echo "<button type='submit' name='book'>Book</button>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p style='color:red;'>No flights available at the moment.</p>";
            }
        }
        ?>
    </div>

    <!-- MY BOOKINGS -->
    <h2 id="my-bookings">My Bookings</h2>
    <div id="my-bookings-container">
        <?php
        if(isset($_SESSION['user_id'])){
            $uid = $_SESSION['user_id'];
            $q = mysqli_query($conn, "
                SELECT b.booking_id, f.flight_no, f.airline_name, f.source, f.destination, f.departure_time, f.arrival_time, b.seat_no, f.price
                FROM bookings b
                JOIN flights f ON b.flight_id = f.flight_id
                WHERE b.passenger_id = $uid
                ORDER BY b.booking_id DESC
            ");
            if(mysqli_num_rows($q) > 0){
                echo "<table><tr><th>Booking ID</th><th>Flight No</th><th>Airline</th><th>From</th><th>To</th><th>Departure</th><th>Arrival</th><th>Seat</th><th>Price</th></tr>";
                while($r = mysqli_fetch_assoc($q)){
                    echo "<tr>";
                    echo "<td>".$r['booking_id']."</td>";
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
                echo "</table>";
            } else {
                echo "<p>No bookings yet.</p>";
            }
        }
        ?>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <p>&copy; 2025 Airline System. All rights reserved.</p>
</footer>

<script src="script.js"></script>
</body>
</html>
