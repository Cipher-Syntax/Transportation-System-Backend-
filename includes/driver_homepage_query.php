<?php

    $database = new Database();
    $conn = $database->getConnection();
    $driver_data = checkDriverLogin($conn);

    $driver_id = $driver_data['id'];
    $_SESSION['driver_id'] = $driver_data['id'];
    
    $query = "SELECT r.*
    FROM rides r
    JOIN users u ON r.passenger = u.id
    WHERE r.driver = :driver_id AND r.status = 'Pending'
    ORDER BY r.ride_id DESC
    LIMIT 1";

    $stmt = $conn->prepare($query);
    $stmt->execute([':driver_id' => $driver_id]);
    $new_request = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // GET ACTIVE TRIPS
    $current_trip_query = "SELECT * FROM rides WHERE driver = :driver_id AND status = 'Active' LIMIT 1";
    $stmt = $conn->prepare($current_trip_query);
    $stmt->execute([':driver_id' => $driver_id]);
    $current_trip = $stmt->fetch(PDO::FETCH_ASSOC);

    // GET THE QUOTA ATTRIBUTE IN THE SETTINGS TABLE
    $query = "SELECT *FROM settings";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // QUOTA
    $quota_query = "SELECT COUNT(*) FROM rides WHERE driver = :driver_id AND status = 'Completed' AND DATE(completed_at) = CURDATE()";
    $stmt = $conn->prepare($quota_query);
    $stmt->execute([':driver_id' => $driver_id]);
    $today_completed_rides = $stmt->fetchColumn();
    $quota_limit = $result['driver_quota'];

        
    if ($today_completed_rides >= $quota_limit) {
        echo "<script>alert('Today`s quota has already been reached. You cannot accept more rides.');</script>";
    } 

    // COMPLETED TRIPS
    $completed_query = "SELECT * FROM rides WHERE driver = :driver_id  AND status = 'Completed' AND DATE(completed_at) = CURDATE() ORDER BY completed_at DESC";

    $stmt = $conn->prepare($completed_query);
    $stmt->execute([':driver_id' => $driver_id]);
    $completed_rides = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // TOTAL EARNINGS
    $total_query = "SELECT SUM(amount) AS total_earnings FROM rides WHERE driver = :driver_id AND status = 'Completed' AND DATE(completed_at) = CURDATE() ";

    $stmt = $conn->prepare($total_query);
    $stmt->execute([':driver_id' => $driver_id]);
    $total_earnings = $stmt->fetchColumn();
?>