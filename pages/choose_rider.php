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

    $query = "SELECT * FROM drivers";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose A Rider</title>
    <link rel="stylesheet" href="../assets/css/choose_rider.css">

    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' >

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>
</head>
<body>
    <?php include("../includes/user_header.php"); ?>
    <div class="container">
        <div class="choose-rider">
            <i class='bx bx-left-arrow-alt' ></i>
            <h1>Choose a rider</h1>
        </div>
        <div class="map-container">
            <div id="map"></div>
        </div>

        <div class="rider-container">
            <h1 class="text-logo">GoMove</h1>
    
            <div class="scroll-container">
                <?php foreach ($drivers as $driver): ?>
                    <input type="hidden" name="driver_id" value="<?= $driver['id']; ?>">
                    <div class="scroll-rider" 
                        data-id="<?= $driver['id']; ?>" 
                        data-name="<?= $driver['firstname'] . ' ' . $driver['lastname']; ?>" 
                        data-rating="<?= $driver['ratings']; ?>" 
                        data-notes="<?= htmlspecialchars($driver['driver_notes']); ?>" 
                        data-license="<?= $driver['license_number']; ?>" 
                        data-image="<?= $driver['driver_profile']; ?>">
                        
                        <div class="left-side">
                            <img src="<?= $driver['driver_profile']; ?>" alt="driver-profile">
                            <div class="driver-info">
                                <p class="driver">Driver: <?= $driver['firstname'] . ' ' . $driver['lastname']; ?></p>
                                <div class="rating"><?= $driver['ratings']; ?></div>
                            </div>
                        </div>
                        <div class="right-side">
                            <img src="../assets/images/car_logo.png" alt="">
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
            
            <div class="select-rider">
                <button type="button">Select Rider</button>
            </div>

            <!-- SLIDE UP DETAILS -->
            <div class="slide-up-rider-details">
                <div class="close-btn">✖</div>
                <div class="rider-details">
                    
                    <h1 class="text-logo">Rider Details</h1>
                    <div class="driver-profile-info">
                        <img src="" id="driver-img" alt="driver-profile">
                        <p class="driver-name" id="driver-name"></p>
                        <div class="rating" id="driver-rating"></div>
                    </div>

                    <div class="driver-notes">
                        <p>Driver Notes:</p>
                        <p class="notes" id="driver-notes"></p>
                    </div>

                    <div class="confirm-rider">
                        <p value="" id="driver-license"></p>
                        <input type="hidden" id="selectedDriverInput">
                        <button id="confirmRiderBtn" name="confirm">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 GoMove</p>
        <a href="../pages/about_us.php" class="about">About Us</a>
    </footer>
    
    <script src="../assets/js/chooses_rider.js"></script>


</body>
</html>