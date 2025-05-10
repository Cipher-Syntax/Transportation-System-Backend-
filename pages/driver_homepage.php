<?php
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/login_checker.php");

    // QUERIES
    require_once("../includes/driver_homepage_query.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Homepage</title>

    <link rel="stylesheet" href="../assets/css/driver_homepage.css">

    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' >

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>
</head>
<body>
    <?php include("../includes/driver_header.php");?>

    <div class="container">

        <div class="second-flex">
            <div class="todays-earnings">
                <p class="earnings"><?php echo "PHP " . $total_earnings ?></p>
                <p class="earnings-text">TODAY'S EARNINGS</p>
            </div>

            <div class="todays-quota">
                <p class="quota"><?= $today_completed_rides ?>/<?= $quota_limit ?></p>
                <p class="quota-text">TODAY'S QUOTA</p>
            </div>

            <div class="overall-rating">
                <p class="o-rating"><?php echo $driver_data['ratings']; ?></p>
                <p class="overall-rating-text">RATING</p>
            </div>
        </div>


        <div class="flex">
            
            <?php if ($new_request): ?>
                <div class="new-ride-request-container">
                    <div class="text-logo">New Request</div>
                    <hr class="line-logo">

                    <div class="request-container">
                        <div class="new-request-text">
                            <div class="request-text">New Request</div>
                            <p class="date-text"><?= date("h:i A", strtotime($new_request['created_at'] ?? 'now')) ?></p>
                        </div>
                        <hr class="line-logo">

                        <div class="pickup-history">
                            <div class="pickup-location">
                                <p class="pickup-text-location">Location:</p>
                                <p class="new-ride-value-location"><?= htmlspecialchars($new_request['location']) ?></p>
                            </div>

                            <div class="pickup-destination">
                                <p class="pickup-text-destination">Destination:</p>
                                <p class="new-ride-value-destination"><?= htmlspecialchars($new_request['destination']) ?></p>
                            </div>

                            <div class="pickup-contact">
                                <p class="pickup-text-contact">Contact:</p>
                                <p class="new-ride-value-contact"><?= htmlspecialchars($new_request['user_contact']) ?></p>
                            </div>

                            <div class="pickup-time">
                                <p class="pickup-text-time">Pickup Time:</p>
                                <p class="new-ride-value-time"><?= htmlspecialchars($new_request['pickup_time']) ?></p>
                            </div>
                        </div>

                        <div class="map-container">
                            <div id="map"></div>
                        </div>

                        <div class="price">
                            <p>Price: ₱ <?= htmlspecialchars($new_request['amount']) ?></p>
                        </div>

                        <div class="btn-links">
                            <input type="hidden" id="newRideId" value="<?= $new_request['ride_id'] ?>">
                            <button class="driver-action" id="accept" data-action="accept">Accept</button>
                            <button class="driver-action" id="decline" data-action="decline">Decline</button>

                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="new-ride-request-container">
                    <div class="text-logo">No New Requests</div>
                    <hr class="line-logo">
                    <p style="padding: 1rem;">You currently have no pending ride requests.</p>
                </div>
            <?php endif; ?>


            
            <div class="current-container">
            <div class="current-trip">Current Trip</div>
            <hr class="line-logo">

            <?php if ($current_trip): ?>
                <div class="current-trip-text">
                    <div class="current-text-logo">In Progress</div>
                    <div class="pending-payment">Pending Payment</div>
                </div>
                <hr class="current-line-logo">
                
                <div class="pickup-history">
                    <div class="pickup-location">
                        <p class="pickup-text-location">Location:</p>
                        <p class="current-ride-value-location"><?= $current_trip['location'] ?></p>
                    </div>

                    <div class="pickup-destination">
                        <p class="pickup-text-destination">Destination:</p>
                        <p class="current-ride-value-destination"><?= $current_trip['destination'] ?></p>
                    </div>

                    <div class="pickup-price">
                        <p class="pickup-text-price">PHP Price:</p>
                        <p class="current-ride-value-price"><?= $current_trip['amount'] ?></p>
                    </div>

                    <div class="confirm-btn">
                        <input type="hidden" id="currentRideId" value="<?= $current_trip['ride_id'] ?>">
                        <button class="driver-action" data-action="confirm_payment">Confirm Payment</button>

                    </div>
                </div>
            <?php else: ?>
                <div class="no-trip">No current active trip</div>
            <?php endif; ?>


               <div class="todays-completed-trip">
                    <div class="todays-trip">Today's Trip</div>
                    <hr class="line-logo">
                    <?php if (!empty($completed_rides)): ?>
                        <?php foreach ($completed_rides as $ride): ?>
                            <div class="history-container">
                                <div class="booking-date">
                                    <p class="date"><?= date('F j, Y - h:i A', strtotime($ride['completed_at'])) ?></p>
                                    <div class="confirm-payment">
                                        <p>Payment Completed</p>
                                    </div>
                                </div>
                                <hr class="line-logo">

                                <div class="pickup-history">
                                    <div class="pickup-location">
                                        <p class="pickup-text-location">Location:</p>
                                        <p class="history-ride-value-location"><?= htmlspecialchars($ride['location']) ?></p>
                                    </div>

                                    <div class="pickup-destination">
                                        <p class="pickup-text-destination">Destination:</p>
                                        <p class="history-ride-value-destination"><?= htmlspecialchars($ride['destination']) ?></p>
                                    </div>

                                    <div class="pickup-price">
                                        <p class="pickup-text-price">PHP Price:</p>
                                        <p class="history-ride-value-price"><?= htmlspecialchars($ride['amount']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-trip">You have no completed trips today.</div>
                    <?php endif; ?>

               </div>
            </div>
    
        </div>
    </div>

    <footer>
        <p>&copy; 2025 GoMove</p>
        <a href="../pages/about_us.php" class="about">About Us</a>
    </footer>
    
    <script src="../assets/js/driver_homepage.js"></script>

</body>
</html>