<?php
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/login_checker.php");

    // QUERIES
    require_once("../includes/user_homepage_query.php");

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoMove - Landing Page</title>
    <link rel="stylesheet" href="../assets/css/user_homepage.css">
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
        <div class="flex">
            <div class="booking-container">
                <div class="text-logo">Need a ride?</div>
                <hr class="line-logo">

                <form class="input-form" id="bookingForm">
                    <div class="location">
                        <label for="location">Location:</label>
                        <input type="text" name="location" id="location" placeholder="Enter location" class="location" required>
                    </div>

                    <div class="destination">
                        <label for="destination">Destination:</label>
                        <input type="text" name="destination" id="destination" placeholder="Enter destination" class="destination" required>
                    </div>

                    <div class="contact">
                        <label for="contact">Contact #:</label>
                        <input type="text" name="contact" id="contact" placeholder="+63" class="contact" required>
                    </div>

                    <div class="pickup">
                        <label for="pickup">Pickup Time:</label>
                        <select name="pickup" id="pickup" class="pickup" required>
                            <option value="15m">In 15 minutes</option>
                            <option value="30m">In 30 minutes</option>
                            <option value="1h">In 1 hour</option>
                        </select>
                    </div>

                    <div class="map-container">
                        <div id="map"></div>
                    </div>
                    
                    <div class="price">
                        <p class="php-price">Price: ₱ </p>
                    </div>
                    <input type="hidden" name="price" id="hiddenPrice">
                    <div id="fare-data" data-basefare="<?php echo $result['base_fare']; ?>"></div>
                    <div id="per-km-rate-data" data-perKmRate="<?php echo $result['per_km_rate']; ?>"></div>


                    <div class="btn-links">
                        <button type="submit" id="bookButton">Book Taxi</button>
                    </div>
                </form>

            </div>

            <div class="history-booking-container">
                <div class="text-logo">Recent Rides</div>
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

        
        <div class="cancel-rides">
            <div class="text-logo">Manage Your Rides</div>
            <hr class="line-logo">

            <?php if ($pending_ride): ?>
                <div class="cancel-container">
                    <div class="booking-date">
                        <p class="date"><?= date("F j, Y - h:i A", strtotime($pending_ride['created_at'])) ?></p>
                        <div class="pending-status"><p><?= htmlspecialchars($pending_ride['status']) ?></p></div>
                    </div>
                    <hr class="line-logo">

                    <div class="pickup-history">
                        <div class="pickup-location">
                            <p class="pickup-text-location">Location:</p>
                            <p class="value-location"><?= htmlspecialchars($pending_ride['location']) ?></p>
                        </div>

                        <div class="pickup-destination">
                            <p class="pickup-text-destination">Destination:</p>
                            <p class="value-destination"><?= htmlspecialchars($pending_ride['destination']) ?></p>
                        </div>

                        <div class="pickup-price">
                            <p class="pickup-text-price">PHP Price:</p>
                            <p class="value-price"><?= number_format($pending_ride['amount'], 2) ?></p>
                        </div>

                        <div class="pickup-contact">
                            <p class="pickup-text-contact">Contact No:</p>
                            <p class="value-contact"><?= $pending_ride['contact_no']; ?></p>
                        </div>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="ride_id" value="<?= $pending_ride['ride_id'] ?>">
                        <button type="submit" name="cancel_ride" id="cancel-rides">Cancel Ride</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="no-rides">
                    <p>You don't have any pending rides.</p>
                </div>
            <?php endif; ?>

            <div class="text-logo" style="margin-top: 20px;">Cancelled Rides</div>
            <hr class="line-logo">

            <?php if (count($cancelled_rides) > 0): ?>
                <?php foreach ($cancelled_rides as $c_ride): ?>
                    <div class="cancel-container">
                        <div class="booking-date">
                            <p class="date"><?= date("F j, Y - h:i A", strtotime($c_ride['created_at'])) ?></p>
                            <div class="cancelled-payment"><p>Cancelled</p></div>
                        </div>
                        <hr class="line-logo">

                        <div class="pickup-history">
                            <div class="pickup-location">
                                <p class="pickup-text-location">Location:</p>
                                <p class="value-location"><?= htmlspecialchars($c_ride['location']) ?></p>
                            </div>

                            <div class="pickup-destination">
                                <p class="pickup-text-destination">Destination:</p>
                                <p class="value-destination"><?= htmlspecialchars($c_ride['destination']) ?></p>
                            </div>

                            <div class="pickup-price">
                                <p class="pickup-text-price">PHP Price:</p>
                                <p class="value-price"><?= number_format($c_ride['amount'], 2) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-rides">
                    <p>You don't have any cancelled rides.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 GoMove Transportation System - Zamboanga City</p>
    </footer>
    <script src="../assets/js/user_homepage.js"></script>

    <script>
        const hasPendingRide = <?= $has_pending_ride ?>;
        const bookingForm = document.getElementById('bookingForm');
        
        bookingForm.addEventListener('submit', function(event) {
            if (hasPendingRide) {
                event.preventDefault();
                alert('You still have a pending ride. cancel or wait for your driver to arrive');
                return false;
            }
        });
    </script>
</body>
</html>