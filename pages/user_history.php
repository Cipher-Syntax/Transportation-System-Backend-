<?php
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/login_checker.php");

    $database = new Database();
    $conn = $database->getConnection();
    $user_data = checkUserLogin($conn);

    
    $user_id = $user_data['id'];
    $sql = "SELECT rides.location, 
            rides.destination, 
            rides.amount, 
            rides.completed_at, 
            drivers.firstname as driver_firstname,
            drivers.lastname as driver_lastname,
            drivers.ratings,
            drivers.driver_profile
        FROM rides 
        JOIN drivers ON rides.driver = drivers.id
        WHERE rides.passenger = ? AND rides.status = 'Completed'
        ORDER BY rides.completed_at DESC";

    $stmt =  $conn->prepare($sql);
    $stmt->execute([$user_id]);

    $recent_rides = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User History</title>

    <link rel="stylesheet" href="../assets/css/user_history.css">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' >
</head>
<body>
    <?php include("../includes/user_header.php"); ?>

    <div class="container">
        <div class="history-booking-container">
            <div class="text-logo">History</div>
            <hr class="line-logo">

            <?php foreach ($recent_rides as $ride): ?>
                <div class="history-container">
                    <div class="booking-date">
                        <p class="date"><?= date("F j, Y - h:i A", strtotime($ride['completed_at'])) ?></p>
                        <div class="confirm-payment"><p>Payment Completed</p></div>
                    </div>
                    <hr class="line-logo">

                    <div class="pickup-history">
                        <div class="pickup-location">
                            <p class="pickup-text-location">Location:</p>
                            <p class="value-location"><?= htmlspecialchars($ride['location']) ?></p>
                        </div>

                        <div class="pickup-destination">
                            <p class="pickup-text-destination">Destination:</p>
                            <p class="value-destination"><?= htmlspecialchars($ride['destination']) ?></p>
                        </div>

                        <div class="pickup-price">
                            <p class="pickup-text-price">PHP Price:</p>
                            <p class="value-price"><?= number_format($ride['amount'], 2) ?></p>
                        </div>

                        <div class="pickup-driver">
                            <img src="<?= htmlspecialchars($ride['driver_profile']) ?>" alt="driver-profile">
                            <div class="driver-pickup-info">
                                <p class="driver">Driver: <?= htmlspecialchars($ride['driver_firstname'] . ' ' . $ride['driver_lastname']) ?></p>
                                <div class="rating"><?= htmlspecialchars($ride['ratings']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <footer>
        <p>&copy; 2025 GoMove</p>
        <a href="../pages/about_us.php" class="about">About Us</a>
    </footer>
</body>
</html>