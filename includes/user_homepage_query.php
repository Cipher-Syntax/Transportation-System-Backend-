<?php

    $database = new Database();
    $conn = $database->getConnection();
    $user_data = checkUserLogin($conn);

    if (isset($_POST['cancel_ride']) && isset($_POST['ride_id'])) {
        $ride_id = $_POST['ride_id'];
        $update_sql = "UPDATE rides SET status = 'Cancelled' WHERE ride_id = ? AND passenger = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->execute([$ride_id, $user_data['id']]);
        
        $_SESSION['cancel_message'] = "Your ride has been cancelled.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_SESSION['booking_success'])) {
        $message = $_SESSION['booking_success'];
        // echo "<script>
        //     window.onload = function() {
        //         alert('$message');
        //     };
        // </script>";
        include("../templates/successfullBooking.php");
        unset($_SESSION['booking_success']);
    }

    if (isset($_SESSION['cancel_message'])) {
        $message = $_SESSION['cancel_message'];
        // echo "<script>
        //     window.onload = function() {
        //         alert('$message');
        //     };
        // </script>";
        include("../templates/cancelBooking.php");
        unset($_SESSION['cancel_message']);
    }

    $user_id = $user_data['id'];
    $sql = "SELECT ride_id, status, user_notified FROM rides WHERE passenger = ? ORDER BY ride_id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    $ride = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ride && $ride['status'] == 'Cancelled' && $ride['user_notified'] == 0) {
        // echo "<script>
        //     window.onload = function() {
        //         alert('Rider declined your request');
        //     };
        // </script>";
        include("../templates/riderDeclinedRequest.php");
        

        // Mark user as notified
        $update = "UPDATE rides SET user_notified = 1 WHERE ride_id = ?";
        $stmtUpdate = $conn->prepare($update);
        $stmtUpdate->execute([$ride['ride_id']]);
    }

    $user_id = $user_data['id'];

    // Get recent completed rides
    $sql = "SELECT 
                r.location,
                r.destination,
                r.amount,
                r.completed_at,
                d.firstname AS driver_firstname,
                d.lastname AS driver_lastname,
                d.ratings,
                d.driver_profile
            FROM rides r
            JOIN drivers d ON r.driver = d.id
            WHERE r.passenger = ? AND r.status = 'Completed'
            ORDER BY r.completed_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    $recent_rides = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

    $pending_sql = "SELECT 
                r.ride_id,
                r.location,
                r.destination,
                drivers.contact_no,
                r.pickup_time,
                r.amount,
                r.created_at,
                r.status
            FROM rides r
            JOIN drivers ON r.driver = drivers.id
            WHERE r.passenger = ? AND r.status = 'Pending' 
            ORDER BY r.created_at DESC
            LIMIT 1";
            
    $pending_stmt = $conn->prepare($pending_sql);
    $pending_stmt->execute([$user_id]);
    $pending_ride = $pending_stmt->fetch(PDO::FETCH_ASSOC);
    

    $cancelled_sql = "SELECT 
                r.ride_id,
                r.location,
                r.destination,
                r.amount,
                r.created_at,
                r.status
            FROM rides r
            WHERE r.passenger = ? AND r.status = 'Cancelled'
            ORDER BY r.created_at DESC
            LIMIT 5";
            
    $cancelled_stmt = $conn->prepare($cancelled_sql);
    $cancelled_stmt->execute([$user_id]);
    $cancelled_rides = $cancelled_stmt->fetchAll(PDO::FETCH_ASSOC);

    $has_pending_ride = $pending_ride ? 'true' : 'false';

    // BASE FARE AND PER KM RATE
    $query = "SELECT *FROM settings";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
?>