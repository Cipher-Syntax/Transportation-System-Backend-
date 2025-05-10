<?php
    session_start();
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/login_checker.php");

    header('Content-Type: application/json');

    $data = json_decode(file_get_contents('php://input'), true);
    $driver_id = $data['driver_id'] ?? '';

    if (!$driver_id) {
        echo json_encode(["status" => "error", "message" => "No driver selected."]);
        exit;
    }

    $database = new Database();
    $conn = $database->getConnection();
    $user_data = checkUserLogin($conn);

    $passenger_id = $user_data['id'];
    $location = $_SESSION['location'] ?? '';
    $destination = $_SESSION['destination'] ?? '';
    $pickup = $_SESSION['pickup'];
    $contact = $_SESSION['contact'] ?? '';
    $price = $_SESSION['price'] ?? '0';
    $status = 'Pending';

    $insertQuery = "INSERT INTO rides (passenger, driver, location, destination, pickup_time, user_contact, amount, status) 
                    VALUES (:passenger, :driver, :location, :destination, :pickup_time, :contact,  :amount, :status)";
    $stmt = $conn->prepare($insertQuery);

    $bind_params = [
        ':passenger' => $passenger_id,
        ':driver' => $driver_id,
        ':location' => $location,
        ':destination' => $destination,
        ':pickup_time' => $pickup,
        ':contact' => $contact,
        ':amount' => $price,
        ':status' => $status
    ];

    $success = $stmt->execute($bind_params);

    if ($success) {
        $_SESSION['booking_success'] = "Successful booking! Please wait for your driver to arrive.";
        echo json_encode(["status" => "success", "message" => "Ride confirmed successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to confirm ride."]);
    }
?>